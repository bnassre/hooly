<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Message\CreateFoodTruckReservation;
use App\Entity\ReserveFoodTrucksFactory;
use App\Entity\ReserveFoodTrucks;
use App\Repository\ReserveFoodTrucksRepository;

#[AsMessageHandler]
class CreateFoodTruckReservationHandler {

     /**
     * @param ReserveFoodTrucksRepository $reserveFoodTrucksRepository
     */
    public function __construct(private readonly ReserveFoodTrucksRepository $reserveFoodTrucksRepository)
    {
    }

    public function __invoke(CreateFoodTruckReservation $message)
    {
        $data = $message->getFoodTruckDto();

        $foodTruckName = $data->getFoodTruckName() ?? null;
        $reservationDate = $data->getReservationDate() ?? null;

        [$startDateWeek, $endDateWeek]= $this->getWeekStartAndEndDate($reservationDate);

        // Verifier s'il existe encore des emplacements pour foodTrucks
        $currentWeekFreeSpaces = $this->checkFreeSpacesCurrentWeek($reservationDate);
        if(!$currentWeekFreeSpaces) {
            return [
                "statut" => 200,
                "message" => 'Erreur: Aucun emplacement disponible pendant la date souhaitée.'
            ];
        }

        // Vérifier si la "foodTruck" a déja effectuée une réservation durant la semaine
        $foodTrucksHasAlreadyReservation = $this->checkFoodTrucksHasBooksCurrentWeek($foodTruckName, $startDateWeek, $endDateWeek);
        if($foodTrucksHasAlreadyReservation) {
            return [
                "statut" => 200,
                "message" => 'Erreur: Vous n\'êtes autorisé à effectuer qu\'une réservation par semaine.'
            ];
        }

        // Création de la réservation
        $reserveFoodTruckEntity = ReserveFoodTrucksFactory::create($foodTruckName, $reservationDate);
        $this->reserveFoodTrucksRepository->add($reserveFoodTruckEntity, flush: true);

        return [
            "statut" => 201,
            "message" => 'Réservation pour le foodTruck "'.$foodTruckName.'" effectuée avec succès le "'.$reservationDate->format('Y-m-d').'"'
        ];
    }

    /**
     * @param \DateTime $reservationDate
     */
    private function checkFreeSpacesCurrentWeek(\DateTime $reservationDate): bool {
        
        $countReservationCurrentWeek = $this->reserveFoodTrucksRepository->countReservedSpacesCurrentWeek($reservationDate);
        $dayOfWeek = $reservationDate->format("N");
        $countFreeSpaces = ReserveFoodTrucks::MAX_RESERVATION_PER_DAY - $countReservationCurrentWeek;
        $countFreeSpaces = $dayOfWeek == 5 ? $countFreeSpaces - 1 : $countFreeSpaces;
        return $countFreeSpaces > 0;
    }

    /**
     * @param string $foodTruckName
     * @param string $startDateWeek
     * @param string $endDateWeek
     */
    private function checkFoodTrucksHasBooksCurrentWeek(string $foodTruckName, string $startDateWeek, string $endDateWeek) : bool {
        $countFoodTrucksReservationCurrentWeek = $this->reserveFoodTrucksRepository->countFoodTrucksReservationCurrentWeek($foodTruckName, $startDateWeek, $endDateWeek);
        return $countFoodTrucksReservationCurrentWeek > 0;
    }

    /**
     * @param \DateTime $reservationDate
     */
    private function getWeekStartAndEndDate(\DateTime $reservationDate): array {
        $date = new \DateTime($reservationDate->format('Y-m-d'));
        $date->setISODate($date->format("Y"), $date->format("W"));
        $ret[] = $date->format('Y-m-d');
        $date->modify('+6 days');
        $ret[] = $date->format('Y-m-d');
        return $ret;
    }
}

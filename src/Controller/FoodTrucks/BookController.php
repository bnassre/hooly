<?php

namespace App\Controller\FoodTrucks;


use App\Requests\ReservationFoodTruckRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use App\Message\CreateFoodTruckReservation;
use App\Dto\CreateFoodTrucksReservationDto;

class BookController extends AbstractController
{

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @Route("/reservate", name="book_foodtrucks", methods={"POST"})
     */
    public function reservate(ReservationFoodTruckRequest $request, MessageBusInterface $messageBus ): JsonResponse
    {
        try {

            $data = $this->serializer->deserialize($request->getContent(), CreateFoodTrucksReservationDto::class, 'json');    
            
            $message = new CreateFoodTruckReservation($data);
            $envelope = $messageBus->dispatch($message);

            $handledStamp = $envelope->last(HandledStamp::class);
            $result = $handledStamp->getResult();

            return $this->json([
                'statut' => $result['statut'],
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'statut' => 400
            ]);
        }
    }

  

}

<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();

        if ($this->autoValidateRequest()) {
            $this->validate();
        }
    }

    public function validate()
    {
        try {
            $errors = $this->validator->validate($this);

            $messages = ['message' => 'validation_failed', 'errors' => []];

            /** @var \Symfony\Component\Validator\ConstraintViolation */
            foreach ($errors as $message) {
                $messages['errors'][] = [
                    'property' => $message->getPropertyPath(),
                    'value' => $message->getInvalidValue(),
                    'message' => $message->getMessage(),
                ];
            }

            if (count($messages['errors']) > 0) {
                $response = new JsonResponse($messages, 400);
                $response->send();

                exit;
            }
        } catch (\Exception $e) {
            dd('ddddzsssss1');
        }

    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    public function getContent(): string
    {
        return $this->getRequest()->getContent();
    }

    protected function populate(): void
    {
        try {
            foreach ($this->getRequest()->toArray() as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->{$property} = $value;
                }
            }
        } catch (\Exception  $e) {
            $response = new JsonResponse([
                "statut" => 400,
                "message" => $e->getMessage()
            ], 400);
            $response->send();
            exit();
        }
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}
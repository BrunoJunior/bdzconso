<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 21/04/18
 * Time: 00:35
 */

namespace App\Validator;


use App\Entity\FuelType;
use App\Entity\Vehicle;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class VehicleValidator
 * Validation for vehicle entity
 * @package App\Validator
 */
class VehicleValidator
{

    /**
     * Run the validation
     * @param Vehicle $vehicle
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function validate(Vehicle $vehicle, ExecutionContextInterface $context, $payload) {
        static::validatePreferredFuelType($vehicle, $context, $payload);
    }

    /**
     * Preferred fuel type validation
     * @param Vehicle $vehicle
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    private static function validatePreferredFuelType(Vehicle $vehicle, ExecutionContextInterface $context, $payload) {
        if (!$vehicle->getPreferredFuelType() instanceof FuelType) {
            return;
        }
        foreach ($vehicle->getCompatibleFuels() as $fuelType) {
            if ($fuelType->getId() === $vehicle->getPreferredFuelType()->getId()) {
                return;
            }
        }
        $context->buildViolation('The preferred fuel type has to be compatible with the vehicle!')
            ->atPath('preferredFuelType')
            ->addViolation();
    }
}
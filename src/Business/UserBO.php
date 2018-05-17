<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 22:05
 */

namespace App\Business;


use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\User;
use App\Entity\UserApi;
use App\Repository\FuelTypeRepository;
use App\Tools\Color;
use App\Tools\TimeCanvas;
use App\Tools\TimeCanvasSerie;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserBO
{

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var FuelingBO
     */
    private $fuelingBO;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserBO constructor.
     * @param ObjectManager $entityManager
     * @param FuelingBO $fuelingBO
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ObjectManager $entityManager, FuelingBO $fuelingBO, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->fuelingBO = $fuelingBO;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param Fueling[] $fuelings
     * @param TimeCanvas $canvas
     */
    public function fillPriceVolumeCanvas(User $user, array $fuelings, TimeCanvas $canvas) {

        $fuelTypes = $this->entityManager->getRepository(FuelType::class)->findAll();
        $seriesByFuelType = [];
        foreach ($fuelTypes as $fuelType) {
            $color = new Color($fuelType->getColor());
            $serie = new TimeCanvasSerie($fuelType->getName(), $color->getRgba());
            $canvas->addSerie($serie);
            $seriesByFuelType[$fuelType->getName()] = $serie;
        }
        foreach ($fuelings as $consumption) {
            $serie = $seriesByFuelType[$consumption->getFuelType()->getName()];
            $serie->addPoint($this->fuelingBO->getVolumePricePoint($consumption));
        }
    }

    /**
     * Persist or detach an api key to an user
     * @param User $user
     * @param bool $activateApi
     * @throws \Exception
     */
    public function manageApiKey(User $user, bool $activateApi) {
        $api = $user->getApi();
        if ($api === null && $activateApi) {
            $api = new UserApi();
            $api->setUser($user);
            // Generate a random api key, retry until generate an unique one
            do {
                $apiKey = base64_encode(random_bytes(10));
            } while ($this->entityManager->getRepository(UserApi::class)->isApiKeyExists($apiKey));
            $api->setApiKey($apiKey);
            $this->entityManager->persist($api);
        } elseif ($api instanceof UserApi && !$activateApi) {
            $this->entityManager->remove($api);
        }
    }

    /**
     * Encode and set password to the user
     * @param User $user
     * @param $password
     */
    public function encodeAndSetPassword(User $user, $password) {
        if (!empty($password)) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        }
    }
}
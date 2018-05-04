<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 30/04/18
 * Time: 22:36
 */

namespace App\Business;


use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\Vehicle;
use App\Repository\FuelTypeRepository;
use App\Tools\TimeCanvasPoint;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class FuelingBO
{

    /**
     * The entity manager
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * DAO FuelType
     * @var FuelTypeRepository
     */
    private $fuelTypeRepo;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \NumberFormatter
     */
    private $numberFormatter;

    /**
     * FuelingBO constructor.
     * @param ObjectManager $entityManager
     * @param FuelTypeRepository $fuelTypeRepo
     * @param LoggerInterface $logger
     */
    public function __construct(ObjectManager $entityManager, FuelTypeRepository $fuelTypeRepo, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->fuelTypeRepo = $fuelTypeRepo;
        $this->numberFormatter = new \NumberFormatter('fr_FR', \NumberFormatter::DECIMAL);
    }

    /**
     * Import from a file
     * @param string $filePath
     * @param Vehicle $vehicle
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function import(string $filePath, Vehicle $vehicle) {
        // Import du fichier
        if (($handle = fopen($filePath, "r")) !== FALSE) { // Lecture du fichier, à adapter
            while (($data = fgetcsv($handle, 1000)) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                $this->importRow($data, $vehicle);
            }
            fclose($handle);
            $this->entityManager->flush();
        }
    }

    /**
     * Import one row
     * @param array $data
     * @param Vehicle $vehicle
     * @param boolean $flush
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function importRow(array $data, Vehicle $vehicle, $flush = false) {
        if (count($data) < 7 || count($data) > 8) {
            $this->logger->error("Bad CSV format!");
            return;
        }
        $data[0] = \DateTime::createFromFormat('d/m/Y', $data[0]);
        $data[1] = $this->fuelTypeRepo->findByName($data[1]);
        for ($i = 2; $i < 7; $i++) {
            $data[$i] = $this->numberFormatter->parse($data[$i]);
            if (!is_float($data[$i])) {
                $this->logger->error("Bad number format!");
                return;
            }
        }
        if (!$data[0] instanceof \DateTime) {
            $this->logger->error("Bad date format!");
            return;
        }
        if (!$data[1] instanceof FuelType) {
            $this->logger->error("Unknown fuel type!");
            return;
        }

        $fueling = new Fueling();
        $fueling->setVehicle($vehicle);
        $fueling->setDate($data[0]);
        $fueling->setFuelType($data[1]);
        $fueling->setVolume((int)($data[2] * 1000));
        $fueling->setVolumePrice((int)($data[3] * 1000));
        $fueling->setAmount((int)($data[4] * 100));
        $fueling->setTraveledDistance((int)($data[5] * 10));
        $fueling->setShowedConsumption((int)($data[6] * 10));
        if (array_key_exists(7, $data)) {
            $fueling->setAdditivedFuel((bool)$data[7]);
        }
        $this->entityManager->persist($fueling);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param Fueling $fueling
     * @return TimeCanvasPoint
     */
    public function getRealConsumptionPoint(Fueling $fueling): TimeCanvasPoint
    {
        return new TimeCanvasPoint($fueling->getDate(), round($fueling->getRealConsumption(), 2));
    }

    /**
     * @param Fueling $fueling
     * @return TimeCanvasPoint
     */
    public function getShowedConsumptionPoint(Fueling $fueling): TimeCanvasPoint
    {
        return new TimeCanvasPoint($fueling->getDate(), round($fueling->getShowedConsumption() / 10.0, 2));
    }

    /**
     * @param Fueling $fueling
     * @return TimeCanvasPoint
     */
    public function getAmountPoint(Fueling $fueling): TimeCanvasPoint
    {
        return new TimeCanvasPoint($fueling->getDate(), round($fueling->getAmount() / 100.0, 2));
    }

    /**
     * @param Fueling $fueling
     * @return TimeCanvasPoint
     */
    public function getVolumePricePoint(Fueling $fueling): TimeCanvasPoint
    {
        return new TimeCanvasPoint($fueling->getDate(), round($fueling->getVolumePrice() / 1000.0, 3));
    }
}
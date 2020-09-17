<?php


namespace App\Controller;


use App\Entity\Objects;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIController extends AbstractController
{
    /**
     * @Route("getListOfObjets/", name="getListOfObjects")
     */
    public function getListOfObjects()
    {
        $objectRepository = $this->getDoctrine()->getRepository(Objects::class);
        $objectsEntities = $objectRepository->findAll();
        $result = [];
        $i = 0;
        /** @var Objects $objectEntity */
        foreach ($objectsEntities as $i => $objectEntity) {
            $address = $objectEntity->getAddress();
            $coordinates = $objectEntity->getCoordinates();
            $description = $objectEntity->getDescription();
            $data = [
                'number' => $i,
                'address' => $address,
                'coordinates' => $coordinates,
                'description' => $description,
            ];
            $result[] = $data;
        }

        return $this->json($result);
    }

    /**
     * @Route("getOneObject/{id}", name="getOneObject")
     */
    public function getOneObject(Objects $objectEntity)
    {
        $address = $objectEntity->getAddress();
        $coordinates = $objectEntity->getCoordinates();
        $description = $objectEntity->getDescription();
        $params = [];
        $facilities = [];
        $paramsEntities = $objectEntity->getObjectsParams();
        $facilitiesEntities = $objectEntity->getObjectsFacilities();
        foreach ($paramsEntities as $paramEntity) {
            $params[] = $paramEntity->getParamsText();
        }
        foreach ($facilitiesEntities as $facilityEntity) {
            $facilities[] = $facilityEntity->getFacilityText();
        }
        $result = [
            'address' => $address,
            'coordinates' => $coordinates,
            'description' => $description,
            'params' => $params,
            'facilities' => $facilities,
        ];

        return $this->json($result);
    }

}
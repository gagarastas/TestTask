<?php


namespace App\Controller;


use App\Dto\FormData;
use App\Entity\Facilities;
use App\Entity\Objects;
use App\Entity\Params;
use App\Entity\Photos;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;


class ObjectsController extends AbstractController
{

    private function getFromForm(Request $request, $tokenId)
    {
        $formData = new FormData();
        $formData->address = $request->request->get('_address');
        $formData->coordinates = $request->request->get('_coordinates');
        $formData->description = $request->request->get('_description');
        $formData->params = $request->request->get('_params') ?? [];
        $formData->facilities = $request->request->get('_facilities') ?? [];
        $formData->photos = $request->files->get("_photos");
        $token = $request->request->get('_csrf_token');

        if (!$this->isCsrfTokenValid($tokenId, $token)) {
            throw new InvalidCsrfTokenException();
        }
        foreach ($formData->photos as $photo) {
            $type = explode('/', $photo->getClientMimeType());
            if ($type[0] !== 'image') {
                $formData->errors[] = 'Можно прикреплять только фотографии';
            }
        }
        if (empty($formData->address)) {
            $formData->errors[] = 'Введите адрес';
        }
        if (empty($formData->coordinates)) {
            $formData->errors[] = 'Введите координаты';
        }
        if (empty($formData->description)) {
            $formData->errors[] = 'Введите описание';
        }
        return $formData;

    }

    /**
     * @Route("/", name="allObjects")
     */
    public function allObjects()
    {
        $objectRepository = $this->getDoctrine()->getRepository(Objects::class);
        $objectsEntities = $objectRepository->findAll();

        $user = $this->getUser();
        $adminFlag = false;
        $roles = [];
        if (isset($user)) {
            $roles = $user->getRoles();
        }
        if (in_array('ROLE_ADMIN', $roles)) {
            $adminFlag = true;
        }

        return $this->render('allObjects.html.twig', ['objectsEntities' => $objectsEntities, 'adminFlag' => $adminFlag]);
    }


    /**
     * @Route("oneObject/{id}", name="oneObject")
     */
    public function oneObject(Objects $objectEntity)
    {
        $user = $this->getUser();

        $adminFlag = false;
        $roles = [];
        if (isset($user)) {
            $roles = $user->getRoles();
        }
        if (in_array('ROLE_ADMIN', $roles)) {
            $adminFlag = true;
        }

        return $this->render('oneObject.html.twig', ['object' => $objectEntity, 'adminFlag' => $adminFlag]);
    }


    /**
     * @Route("/addObject/", name="addObject")
     */
    public function addObject(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $template = "addObject.html.twig";
        if ($request->isMethod('GET')) {
            return $this->render($template, ['errors' => []]);
        }


        $formData = $this->getFromForm($request, 'addObject');

        if (!empty($formData->errors)) {
            return $this->render($template, ['errors' => $formData->errors]);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $objectEntity = new Objects();
        $objectEntity->setAddress($formData->address);
        $objectEntity->setCoordinates($formData->coordinates);
        $objectEntity->setDescription($formData->description);


        /** @var Params $paramEntity */
        foreach ($formData->params as $param) {
            $paramEntity = $entityManager->getRepository(Params::class)->findOneBy(['paramText' => $param]);
            if (is_null($paramEntity)) {
                throw $this->createNotFoundException('such parameter does not exist in the database ' . $param);
            }
            $objectEntity->addObjectsParam($paramEntity);

        }


        /** @var Facilities $facilityEntity */
        foreach ($formData->facilities as $facility) {
            $facilityEntity = $entityManager->getRepository(Facilities::class)->findOneBy(['facilityText' => $facility]);
            if (is_null($facilityEntity)) {
                throw $this->createNotFoundException('such facility does not exist in the database ' . $facility);
            }
            $objectEntity->addObjectsFacility($facilityEntity);

        }

        $entityManager->persist($objectEntity);
        $entityManager->flush();

        $id = $objectEntity->getId();
        $uploadDerectory = "../public/photos/$id/";
        @mkdir($uploadDerectory);

        foreach ($formData->photos as $photo) {
            $photoEntity = new Photos();
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move($uploadDerectory, $filename);
            $photoEntity->setObjectId($objectEntity);
            $photoEntity->setPath($uploadDerectory . $filename);
            $entityManager->persist($photoEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('allObjects');
    }

    /**
     * @Route("/removeObject/{id}", name="removeObject")
     */
    public function removeObject(Objects $objectEntity)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $id = $objectEntity->getId();
        $uploadDirectory = "../public/photos/$id/";
        $entityManager->remove($objectEntity);
        $entityManager->flush();

        if (file_exists($uploadDirectory)) {
            foreach (glob($uploadDirectory . "*") as $file) {
                unlink($file);
            }
        }

        return $this->redirectToRoute('allObjects');
    }

    /**
     * @Route("/updateObject/{id}", name="updateObject")
     */
    public function updateObject(Objects $objectEntity, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $template = 'updateObject.html.twig';
        $entityManager = $this->getDoctrine()->getManager();

        if ($request->isMethod('GET')) {
            return $this->render($template, ['oldObjectEntity' => $objectEntity, 'errors' => []]);
        }

        $formData = $this->getFromForm($request, 'updateObject');

        if (!empty($formData->errors)) {
            return $this->render($template, ['oldObjectEntity' => $objectEntity, 'errors' => $formData->errors]);
        }

        $objectEntity->setAddress($formData->address);
        $objectEntity->setCoordinates($formData->coordinates);
        $objectEntity->setDescription($formData->description);

        $paramRepository = $entityManager->getRepository(Params::class);
        $containParamsEntities = $objectEntity->getObjectsParams();

        foreach ($containParamsEntities as $containParamEntity) {
            if ($key = array_search($containParamEntity->getParamsText(), $formData->params)) {
                unset($formData->params[$key]);
            } else {
                $objectEntity->removeObjectsParam($containParamEntity);
            }
        }
        foreach ($formData->params as $param) {
            $paramEntity = $paramRepository->findOneBy(['paramText' => $param]);
            if (is_null($paramEntity)) {
                throw $this->createNotFoundException('such parameter does not exist in the database ' . $param);
            }
            $objectEntity->addObjectsParam($paramEntity);
        }


        $facilityRepository = $entityManager->getRepository(Facilities::class);
        $containFacilitiesEntities = $objectEntity->getObjectsFacilities();

        foreach ($containFacilitiesEntities as $containFacilityEntity) {
            if ($key = array_search($containFacilityEntity->getFacilityText(), $formData->facilities)) {
                unset($formData->facilities[$key]);
            } else {
                $objectEntity->removeObjectsFacility($containFacilityEntity);
            }
        }
        foreach ($formData->facilities as $facility) {
            $facilityEntity = $facilityRepository->findOneBy(['facilityText' => $facility]);
            if (is_null($facilityEntity)) {
                throw $this->createNotFoundException('such facility does not exist in the database ' . $facility);
            }
            $objectEntity->addObjectsFacility($facilityEntity);
        }

        $entityManager->persist($objectEntity);
        $entityManager->flush();

        $id = $objectEntity->getId();
        $uploadDirectory = "../public/photos/$id/";
        $photoEntity = $objectEntity->getPhotos();;
        if (isset($photoEntity) && file_exists($uploadDirectory)) {
            foreach (glob($uploadDirectory . "*") as $file) {
                unlink($file);
            }
            foreach ($photoEntity as $value) {
                $entityManager->remove($value);
                $entityManager->flush();
            }
        }

        @mkdir($uploadDirectory);
        foreach ($formData->photos as $photo) {
            $newPhotoEntity = new Photos();
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move($uploadDirectory, $filename);
            $newPhotoEntity->setObjectId($objectEntity);
            $newPhotoEntity->setPath($uploadDirectory . $filename);
            $entityManager->persist($newPhotoEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('allObjects');
    }


}
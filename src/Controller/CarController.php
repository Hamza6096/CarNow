<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/car')]
class CarController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/', name: 'car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $cars = $carRepository->findSearch($data);
//        dd($data);
        return $this->render('car/index.html.twig', [
//            'cars' => $carRepository->findAll(),
            'cars' =>$cars,
            'form' => $form->createView()
        ]);
    }

//    // Route pour la recherche pas date
//    #[Route('/', name: 'car_index', methods: ['GET'])]
//    public function index(Request $request, CarRepository $carRepository): Response
//    {
//        $filters = $request->query->all();
//        return $this->render('car/index.html.twig', [
//            'cars' => $carRepository->filter($filters),
//        ]);
//    }




    #[Route('/new/user{idUser}', name: 'car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarRepository $carRepository, UserRepository $userRepository, SluggerInterface $slugger): Response
    {
        $idUser = $request->get('idUser');
        $owner = $userRepository->find($idUser);
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('car_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('danger', 'erreur upload');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $car->setImage($newFilename);
            }

            $car->setOwner($owner);
            $carRepository->add($car, true);

            return $this->redirectToRoute('car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, CarRepository $carRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFileEdit = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFileEdit) {
                $originalFilename = pathinfo($brochureFileEdit->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFileEdit->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFileEdit->move(
                        $this->getParameter('car_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('danger', 'erreur upload');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $car->setImage($newFilename);
            }

            $carRepository->add($car, true);

            return $this->redirectToRoute('car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, CarRepository $carRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $carRepository->remove($car, true);
        }

        return $this->redirectToRoute('car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'car_delete', methods: ['POST'])]
    public function deletImage()
    {

    }
}

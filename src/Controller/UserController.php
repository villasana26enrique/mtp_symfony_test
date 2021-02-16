<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserForm;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Gedmo\Sluggable\Util\Urlizer;

class UserController extends AbstractController
{

    /** @var \Doctrine\Persistence\ObjectRepository */
    private $userRepo;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->userRepo = $entityManager->getRepository('App:User');
        $this->encoder = $encoder;
    }

    /**
     * @Route("/user", name="user_list", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->userRepo->findAll();
        $data = array();
        foreach ($users as $user) {
            $data[] = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "country" => $user->getCountry()
            ];
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $data
        ]);
    }

    /**
     * @Route("/user/create", name="create_user")
     */
    public function create(Request $request) {
        $user = new User();

        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/user_photo';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );

                $user->setPhoto($newFilename);
            }

            $encoded = $this->encoder->encodePassword($user, $form->getData()->getPassword());
            $user->setPassword($encoded);
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            $request->getSession()->set('user_created', true);
            $this->addFlash('success', 'User created!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/form.html.twig', [
            'controller_name' => 'UserController',
            'create_user' => true,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("user/{id}", name="update_user")
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepo->findOneBy(['id' => $id]);

        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/user_photo';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );

                $user->setPhoto($newFilename);
            }
            $encoded = $this->encoder->encodePassword($user, $form->getData()->getPassword());
            $user->setPassword($encoded);
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            $request->getSession()->set('user_updated', true);
            $this->addFlash('success', 'User updated!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/form.html.twig', [
            'controller_name' => 'UserController',
            'create_user' => false,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("user/delete/{id}", name="delete_user")
     */
    public function delete($id)
    {
        $user = $this->userRepo->findOneBy(['id' => $id]);
        $this->entityManager->remove($user);
        $this->entityManager->flush($user);
        return $this->redirectToRoute('user_list');
    }
}

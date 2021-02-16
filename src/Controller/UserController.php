<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\CreateUserForm;

class UserController extends AbstractController
{

    /** @var \Doctrine\Persistence\ObjectRepository */
    private $userRepo;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepo = $entityManager->getRepository('App:User');
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

        $form = $this->createForm(CreateUserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            $request->getSession()->set('user_created', true);
            $this->addFlash('success', 'Usuario creado!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("user/{id}", name="update_user", methods={"PUT"})
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepo->findOneBy(['id' => $id]);

        if (!empty($request->request->get('email')))
            $user->setEmail($request->request->get('email'));

        if (!empty($request->request->get('password')))
            $user->setPassword($request->request->get('password'));

        if (!empty($request->request->get('country')))
            $user->setCountry($request->request->get('country'));

        $updatedUser = $this->userRepo->update($user);
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function delete($id)
    {
        $user = $this->userRepo->findOneBy(['id' => $id]);
        $this->userRepo->delete($user);
        return $this->redirectToRoute('user_list');
    }
}

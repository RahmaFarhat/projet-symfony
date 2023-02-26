<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ManagerRepository;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/classroom/read', name: 'app_classroom_read')]
public function read(ManagerRegistry $doctrine):Response
{
    $repository=$doctrine->getRepository(Classroom::class);
    $list =$repository->findAll();
    return $this->render('classroom/index.html.twig',[
        'classrooms' => $list,
    ]);
}
#[Route('/classroom/read2', name: 'app_classroom_read2')]
    public function read2(ClassroomRepository $repository): Response
    {
        $list = $repository->findAll();
        return $this->render('classroom/read.html.twig', [
            'classrooms' => $list,
        ]);
    }
#[Route('/classroom/delete/{id}', name: 'app_classroom_delete')]
public function delete(ClassroomRepository $repository,$id,ManagerRegistry $doctrine):Response
{
$em = $doctrine ->getManager();
$classroom = $repository -> find($id);
$em -> remove($classroom);
$em -> flush();
return $this->redirectToRoute('app_classroom_read');

}

#[Route('/classroom/create', name: 'app_classroom_create')]
public function create(ManagerRegistry $doctrine,Request $request,ClassroomRepository $crp):Response
{
    $classroom = new Classroom();

    $form = $this->createForm(ClassroomType::class, $classroom);
    $form->handleRequest($request);
    // traiter la requete reçu (handleRequest)
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        // $classroomRepository->save($classroom, true);
        $em->persist($classroom);
        $em->flush();
        return $this->redirectToRoute('app_classroom_read');
    }
    return $this->renderForm('classroom/create.html.twig', [
        'form' => $form,
    ]);
}



}
<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/student/read', name: 'app_student_read')]
    public function read(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Student::class);
        $list =$repository->findAll();
        return $this->render('student/index.html.twig',[
            'students' => $list,
        ]);
    }
    #[Route('/student/read2', name: 'app_student_read2')]
        public function read2(StudentRepository $repository): Response
        {
            $list = $repository->findAll();
            return $this->render('student/read.html.twig', [
                'students' => $list,
            ]);
        }
        #[Route('/student/delete/{id}', name: 'app_student_delete')]
        public function delete(StudentRepository $repository,$id,ManagerRegistry $doctrine):Response
        {
        $em = $doctrine ->getManager();
        $student = $repository -> find($id);
        $em -> remove($student);
        $em -> flush();
        return $this->redirectToRoute('app_student_read');
        
        }

        #[Route('/student/create', name: 'app_student_create')]
        public function create(ManagerRegistry $doctrine,Request $request,StudentRepository $crp):Response
        {
            $student = new Student();
        
            $form = $this->createForm(StudentType::class, $student);
            $form->handleRequest($request);
            // traiter la requete reçu (handleRequest)
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                // $StudentRepository->save($student, true);
                $em->persist($student);
                $em->flush();
                return $this->redirectToRoute('app_student_read');
            }
            return $this->renderForm('student/create.html.twig', [
                'form' => $form,
            ]);
        }
        

























}

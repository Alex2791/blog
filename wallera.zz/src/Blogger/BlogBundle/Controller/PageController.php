<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
            ->getLatestBlogs();



        return $this->render('BloggerBlogBundle:Page:index.html.twig',array('blogs' => $blogs ));
       // return $this->render('BloggerBlogBundle:index.html.twig');
    }
    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function contactAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();

        //exit;
       /* dump($request->getMethod()) ;
        exit;*/
        if ($request->getMethod() == 'POST') {

           // $form->bind($request);
            $form->submit($request);
            if ($form->isValid()) {
                // Выполнение некоторого действия, например, отправка письма.

                // Редирект - это важно для предотвращения повторного ввода данных в форму,
                // если пользователь обновил страницу.
                //dump($_POST);
                $contact =$request->get('contact');

                if($contact['name'])
                {
                    $news = new Enquiry();
                    $news->setName($contact['name']);
                    $news->setBody($contact['body']);
                    $news->setEmail($contact['email']);
                    $news->setSubject($contact['subject']);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($news);
                    $em->flush();
                }
                //return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }
        }
        else
        {
            return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
                'form' => $form->createView()
            ));
        }


    }
    public function sidebarAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $tags = $em->getRepository('BloggerBlogBundle:Blog')
            ->getTags();

        $tagWeights = $em->getRepository('BloggerBlogBundle:Blog')
            ->getTagWeights($tags);

        $commentLimit   = $this->container
            ->getParameter('blogger_blog.comments.latest_comment_limit');

        $latestComments = $em->getRepository('BloggerBlogBundle:Comment')
            ->getLatestComments($commentLimit);
        return $this->render('BloggerBlogBundle:Page:sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'tags' => $tagWeights
        ));
    }




}
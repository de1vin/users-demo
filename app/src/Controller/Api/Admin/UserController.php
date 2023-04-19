<?php

namespace App\Controller\Api\Admin;


use App\Dto\User\CreateUserDto;
use App\Dto\User\SearchUserDto;
use App\Http\AbstractBaseController;
use App\Http\Resolver\BodyValue;
use App\Http\Resolver\QueryParameter;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractBaseController
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * @param SearchUserDto $searchDto
     * @param int           $page
     * @param int           $perPage
     *
     * @return Response
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function listAction(
        #[QueryParameter] SearchUserDto $searchDto,
        #[QueryParameter] int $page = 1,
        #[QueryParameter] int $perPage = 10
    ): Response {
        $searchResult = $this->userService->search($searchDto, $page, $perPage);

        return $this->json($searchResult);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    #[Route('/{id}', name: 'view', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function viewAction(int $id): Response
    {
        $user = $this->userService->findOr404($id);

        return $this->json($user);
    }

    /**
     * @param CreateUserDto $userDto
     * @param Request       $request
     *
     * @return Response
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function createAction(
        #[BodyValue] CreateUserDto $userDto,
        Request $request
    ): Response
    {
        $user = $this->userService->create($userDto);

        return $this->json($user);
    }


    /**
     * @param CreateUserDto $userDto
     *
     * @return Response
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateAction(#[BodyValue] CreateUserDto $userDto): Response
    {
        $user = $this->userService->create($userDto);

        return $this->json($user);
    }

    /**
     * @return RedirectResponse
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteAction(): RedirectResponse
    {
        return $this->redirect('admin_user_list');
    }
}

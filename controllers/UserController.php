<?php

namespace Controller;

use Core\Controller;
use Core\RedirectResponse;
use Core\Request;
use Core\Response;
use Exception;
use Model\User;
use ReflectionException;

class UserController extends Controller
{
    /**
     * @throws ReflectionException
     */
    public function index(Request $request, $id=""): Response
    {
        $user = $this->repository->findById(User::class, $id);
        $permission = $this->permissions->getRole($user);
        return new Response($this->template->render("users/index", ["id" => $id, "name" => $user->getName(), "email" => $user->getEmail(), "permission" => $permission["name"]]));
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function editUser(Request $request, $id=""): RedirectResponse
    {
        $body = $request->getRequestBody();
        $user = $this->repository->findById(User::class, $id);
        if (isset($body["permission"])){
            $this->permissions->addRole($user, $body["permission"]);
        }

        return new RedirectResponse("/");
    }

    /**
     * @throws ReflectionException
     */
    public function deleteUser(Request $request, $id=''): RedirectResponse
    {
        $user = $this->repository->findById(User::class, $id);
        $this->entityManager->deleteEntity($user);

        return new RedirectResponse("/");
    }
}
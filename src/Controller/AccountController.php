<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\Type\AccountType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountController extends ApiController
{
	/**
	 * Возвращает список зарегистрированных аккаунтов
	 *
	 * @Route("/api/v1/accounts", methods={"GET"})
	 * @param Request $request экземпляр HTTP запроса
	 * @return object экземпляр ответа (список аккаунтов)
	 */
	public function indexAction(Request $request): Response
	{
		$Accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();
		return $this->respond($Accounts);
	}

	/**
	 * Создаёт новый аккаунт
	 *
	 *  Если не установлен friendsofsymfony/rest-bundle
	    необходимо перед $form->handleRequest($request); добавить блок кода

	  	$data = $request->toArray();
		$request->request->set('email',$data['email']);
		$request->request->set('phone',$data['phone']);

	  	чтобы корректно заполнялась форма <- request (content-type=application/json)
	 *
	 * @Route("/api/v1/accounts", methods={"POST"})
	 * @param Request $request HTTP запрос
	 * @return object экземпляр ответа (созданный аккаунт)
	 */
	public function createAction(Request $request): Response
	{
		$form = $this->buildForm(AccountType::class);

		$form->handleRequest($request);

		if (!$form->isSubmitted() || !$form->isValid()){
			return $this->respond($form,Response::HTTP_BAD_REQUEST);
		}

		/* @var Account $account */
		$account = $form->getData();

		$this->getDoctrine()->getManager()->persist($account);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond($account);
	}

	/**
	 * Обновляет аккаунт клиента
	 *
	 * @Route("/api/v1/account/{aid}", methods={"PATCH"})
	 * @param Request $request HTTP запрос
	 *
	 * @throws NotFoundHttpException
	 *
	 * @return object экземпляр ответа (обновлённый аккаунт)
	 */
	public function updateAction(Request $request): Response
	{
		$accountId = $request->get('aid');

		$account = $this->getDoctrine()->getRepository(Account::class)->findOneBy([
			'id' => $accountId,
		]);

		if (!$account){
			throw new NotFoundHttpException("Не найден аккаунт {$accountId}");
		}

		$form = $this->buildForm(AccountType::class, $account,[
			'method' => $request->getMethod()
		]);

		$form->handleRequest($request);

		if (!$form->isSubmitted() || !$form->isValid()){
			return $this->respond($form,Response::HTTP_BAD_REQUEST);
		}

		/* @var Account $account */
		$account = $form->getData();
		// хэшируем пароль
		$account->setPassword($request->get('password'));

		$this->getDoctrine()->getManager()->persist($account);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond($account);
	}

	/**
	 * Удаляет аккаунт клиента
	 *
	 * @Route("/api/v1/account/{aid}", methods={"DELETE"})
	 * @param Request $request HTTP запрос
	 *
	 * @throws NotFoundHttpException
	 *
	 * @return Response экземпляр ответа (удалённый аккаунт)
	 */
	public function deleteAction(Request $request): Response
	{
		$AccountId = $request->get('aid');
		$Account = $this->getDoctrine()->getRepository(Account::class)->findOneBy([
			'id' => $AccountId
		]);

		if (!$Account){
			throw new NotFoundHttpException('Аккаунт не найден');
		}

		$this->getDoctrine()->getManager()->remove($Account);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond(['deleted' => $AccountId]);
	}
}

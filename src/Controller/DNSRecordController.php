<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\DNSRecord;
use App\Form\Type\DNSRecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DNSRecordController extends ApiController
{
	/**
	 * Возвращает список DNS-записей клиента
	 *
	 * @Route("/api/v1/account/{id}/records", methods={"GET"})
	 * @param Request $request экземпляр HTTP запроса
	 * @return object экземпляр ответа (список DNS-записей)
	 */
	public function indexAction(Request $request): Response
	{
		$DNSRecords = $this->getDoctrine()->getRepository(DNSRecord::class)->findAll();
		return $this->respond($DNSRecords);
	}

	/**
	 * Возвращает статистику использования аккаунтов клиентов
	 *
	 * @Route("/api/v1/account/{id}/stat", methods={"GET"})
	 * @param Request $request экземпляр HTTP запроса
	 * @return object экземпляр ответа (список аккаунтов)
	 */
	public function statAction(Request $request): Response
	{
		$conn = $this->getDoctrine()->getRepository(DNSRecord::class)->getConnection();
		$sql = "	SELECT COUNT(1) as count
					  FROM
						(SELECT CONCAT(`name`,':',content) AS account, COUNT(1) AS count
						   FROM dnsrecord 
						  WHERE `proxiable` = true AND `proxied` != true AND aid = :client_id1
								AND COALESCE(price,diler_price) >= :price1
								AND `created_at` >= :date_from1 AND `created_at` <= :date_to1
						        # интервал за последние frequency часов
								AND updated_at >= NOW()- INTERVAL :frequency HOUR 
						        GROUP BY CONCAT(`name`,':',content)
								ORDER BY count DESC
						) subq
						UNION ALL
					SELECT COUNT(1) as count
					  FROM
					  (SELECT CONCAT(`name`,':',content) AS account, COUNT(1) AS count
						   FROM dnsrecord 
						  WHERE `proxiable` = true AND `proxied` != true AND aid = :client_id2
								AND COALESCE(price,diler_price) >= :price2
								AND `created_at` >= :date_from2 AND `created_at` <= :date_to2
						        GROUP BY CONCAT(`name`,':',content)
								ORDER BY count DESC
						) subq2";
		$stmt = $conn->prepare($sql);
		$stmt->execute([
			':client_id1' => $request->get('aid'),
			':price1' => $request->get('price') ?? 0,
			':date_from1' => $request->get('date_from') ?? '1900-01-01 00:00:00',
			':date_to1' => $request->get('date_to') ?? '2030-01-01 00:00:00',
			':frequency' => $request->get('frequency') ?? 24,
			':client_id2' => $request->get('aid'),
			':price2' => $request->get('price') ?? 0,
			':date_from2' => $request->get('date_from') ?? '1900-01-01 00:00:00',
			':date_to2' => $request->get('date_to') ?? '2030-01-01 00:00:00',
		]);
		$DNSRecords = $stmt->fetchAll();
		return $this->respond($DNSRecords);
	}

	/**
	 * Создаёт новую DNS-запись клиента
	 *
	 *  Если не установлен friendsofsymfony/rest-bundle
		необходимо перед $form->handleRequest($request); добавить блок кода

		$data = $request->toArray();
		$request->request->set('code',$data['code']);
		$request->request->set('title',$data['title']);
		$request->request->set('price',$data['price']);

		чтобы корректно заполнялась форма <- request (content-type=application/json)
	 *
	 * @Route("/api/v1/account/{aid}/record", methods={"POST"})
	 * @param Request $request HTTP запрос
	 * @return object экземпляр ответа (созданная DNS-запись)
	 */
	public function createAction(Request $request): Response
	{
		$accountId = $request->get('aid');

		$account = $this->getDoctrine()->getRepository(Account::class)->findOneBy([
			'id' => $accountId,
		]);

		if (!$account){
			throw new NotFoundHttpException("Не найден аккаунт {$accountId}");
		}

		$form = $this->buildForm(DNSRecordType::class);

		$form->handleRequest($request);

		if (!$form->isSubmitted() || !$form->isValid()){
			return $this->respond($form,Response::HTTP_BAD_REQUEST);
		}

		/* @var DNSRecord $DNSRecord */
		$DNSRecord = $form->getData();
		$DNSRecord->setAid($accountId);

		$this->getDoctrine()->getManager()->persist($DNSRecord);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond($DNSRecord);
	}

	/**
	 * Обновляет DNS-запись клиента
	 *
	 * @Route("/api/v1/account/{id}/record/{rid}", methods={"PATCH"})
	 * @param Request $request HTTP запрос
	 *
	 * @throws NotFoundHttpException
	 *
	 * @return object экземпляр ответа (обновлённая DNS-запись)
	 */
	public function updateAction(Request $request): Response
	{
		$recordId = $request->get('rid');

		$DNSRecord = $this->getDoctrine()->getRepository(DNSRecord::class)->findOneBy([
			'id' => $recordId,
		]);

		if (!$DNSRecord){
			throw new NotFoundHttpException("Не найдено записи {$recordId}");
		}

		$form = $this->buildForm(DNSRecordType::class, $DNSRecord,[
			'method' => $request->getMethod()
		]);

		$form->handleRequest($request);

		if (!$form->isSubmitted() || !$form->isValid()){
			return $this->respond($form,Response::HTTP_BAD_REQUEST);
		}

		/* @var DNSRecord $DNSRecord */
		$DNSRecord = $form->getData();

		$this->getDoctrine()->getManager()->persist($DNSRecord);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond($DNSRecord);
	}

	/**
	 * Удаляет DNS-запись
	 *
	 * @Route("/api/v1/account/{id}/record/{rid}", methods={"DELETE"})
	 * @param Request $request HTTP запрос
	 *
	 * @throws NotFoundHttpException
	 *
	 * @return Response экземпляр ответа (удалённая DNS-запись)
	 */
	public function deleteAction(Request $request): Response
	{
		$recordId = $request->get('id');

		$DNSRecord = $this->getDoctrine()->getRepository(DNSRecord::class)->findOneBy([
			'id' => $recordId,
		]);

		if (!$DNSRecord){
			throw new NotFoundHttpException("Не найдено записи {$recordId}");
		}

		$this->getDoctrine()->getManager()->remove($DNSRecord);
		$this->getDoctrine()->getManager()->flush();

		return $this->respond(['deleted' => $recordId]);
	}
}

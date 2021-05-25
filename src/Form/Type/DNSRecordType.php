<?php

namespace App\Form\Type;

use App\Entity\DNSRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class DNSRecordType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('aid')
			->add('type',TextType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Тип DNS-записи не может быть пустым'
					]),
					new Length([
						'max' => 32,
					])
				]
			])
			->add('name',TextType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Наименование DNS-записи не может быть пустым'
					]),
					new Length([
						'max' => 255,
					])
				]
			])
			->add('content',TextType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Содержимое DNS-записи не может быть пустым'
					]),
					new Length([
						'max' => 32,
					])
				]
			])
			->add('proxiable',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 5,
					])
				]
			])
			->add('proxied',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 5,
					])
				]
			])
			->add('ttl',IntegerType::class, [
				'constraints' => [
					new Length([
						'max' => 5,
					])
				]
			])
			->add('locked',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 5,
					])
				]
			])
			->add('created_at')
			->add('meta',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 4096,
					])
				]
			])
			->add('price',IntegerType::class, [])
			->add('diler_price',IntegerType::class, [])
			->add('updated_at');
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => DNSRecord::class,
		]);
	}
}

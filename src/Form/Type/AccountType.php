<?php

namespace App\Form\Type;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;

class AccountType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('aid',TextType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Account GUID не может быть пустым'
					]),
					new Length([
						'max' => 32,
					])
				]
			])
			->add('email',EmailType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Email не может быть пустым'
					]),
					new Email([
						'message' => 'Неправильный email'
					])
				]
			])
			->add('password',TextType::class, [
				'constraints' => [
					new NotNull([
						'message' => 'Пароль не должен быть пустым'
					]),
					new Length([
						'max' => 64,
					]),
				]
			])
			->add('name',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 64,
					])
				]
			])
			->add('settings',TextType::class, [
				'constraints' => [
					new Length([
						'max' => 4096,
					])
				]
			])
			->add('created_at');
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Account::class,
		]);
	}
}

<?php

declare(strict_types = 1);

namespace Lookyman\PHPStan\Symfony\Type;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class ControllerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
	use ContainerDynamicReturnTypeExtensionTrait;

	public function getClass(): string
	{
		return Controller::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'get';
	}

}

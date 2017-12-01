<?php

declare(strict_types = 1);

namespace Lookyman\PHPStan\Symfony\Type;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ContainerInterfaceDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
	use ContainerDynamicReturnTypeExtensionTrait;

	public function getClass(): string
	{
		return ContainerInterface::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'get';
	}

}

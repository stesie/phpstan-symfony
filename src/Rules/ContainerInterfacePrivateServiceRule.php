<?php

declare(strict_types = 1);

namespace Lookyman\PHPStan\Symfony\Rules;

use Lookyman\PHPStan\Symfony\ServiceMap;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeWithClassName;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ContainerInterfacePrivateServiceRule implements Rule
{

	/**
	 * @var ServiceMap
	 */
	private $serviceMap;

	public function __construct(ServiceMap $symfonyServiceMap)
	{
		$this->serviceMap = $symfonyServiceMap;
	}

	public function getNodeType(): string
	{
		return MethodCall::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node instanceof MethodCall) {
			throw new \LogicException();
		}

		if ($node->name !== 'get') {
			return [];
		}

		$type = $scope->getType($node->var);

		if (!$type instanceof TypeWithClassName) {
			return [];
		}

		$services = $this->serviceMap->getServices();
		return in_array($type->getClassName(), [ContainerInterface::class, Controller::class])
			&& isset($node->args[0])
			&& $node->args[0] instanceof Arg
			&& $node->args[0]->value instanceof String_
			&& \array_key_exists($node->args[0]->value->value, $services)
			&& !$services[$node->args[0]->value->value]['public']
			? [\sprintf('Service "%s" is private.', $node->args[0]->value->value)]
			: [];
	}

}

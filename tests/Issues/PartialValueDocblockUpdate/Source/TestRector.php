<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\PartialValueDocblockUpdate\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class TestRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('uff', []);
    }

    public function getNodeTypes(): array
    {
        return [
            ClassMethod::class,
        ];
    }

    public function refactor(Node $node)
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $routeDoctrineAnnotationTagValueNode = $phpDocInfo->getByAnnotationClass('Symfony\Component\Routing\Annotation\Route');

        if (! $routeDoctrineAnnotationTagValueNode instanceof DoctrineAnnotationTagValueNode) {
            return null;
        }

        $defaultsArrayItem = $routeDoctrineAnnotationTagValueNode->getValue('defaults');
        if (! $defaultsArrayItem instanceof ArrayItemNode) {
            $routeDoctrineAnnotationTagValueNode->values[] = new ArrayItemNode(
                new CurlyListNode(),
                'defaults',
            );

            return $node;
        }

        return null;
    }
}

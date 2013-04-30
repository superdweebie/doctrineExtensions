<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\CakeWithSecrets;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\SecretIngredient;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Ingredient;

class AccessControlledReferenceDenyTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $manifest = $this->getManifest(['extensionConfigs' => [
            'Sds\DoctrineExtensions\AccessControl' => true,
            'Sds\DoctrineExtensions\Serializer' => true
        ]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->serializer = $manifest->getServiceManager()->get('serializer');
    }

    public function testSerializeAllow(){

        $documentManager = $this->documentManager;

        //bake the cake. Hmm yum.
        $cake = new CakeWithSecrets();
        $cake->setIngredients([
            new Ingredient('flour'),
            new Ingredient('sugar')
        ]);

        $chocolate = new SecretIngredient('chocolate');
        $strawberry = new SecretIngredient('strawberry');

        $cake->setSecretIngredients([
            $chocolate,
            $strawberry
        ]);

        //Persist cake and clear out documentManager
        $documentManager->persist($cake);
        $documentManager->flush();
        $id = $cake->getId();
        $documentManager->clear();

        $cake = $documentManager->getRepository('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\CakeWithSecrets')->findOneBy(['id' => $id]);

        $this->serializer->setMaxNestingDepth(1);
        $array = $this->serializer->toArray($cake, $documentManager);

        $this->assertCount(2, $array['ingredients']);
        $this->assertFalse(isset($array['secretIngredients']));

    }
}
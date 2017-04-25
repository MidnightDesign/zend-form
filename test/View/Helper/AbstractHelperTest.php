<?php declare(strict_types=1);
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Form\View\Helper;

use Zend\Escaper\Escaper;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\I18n\Translator\Translator;

/**
 * Tests for {@see \Zend\Form\View\Helper\AbstractHelper}
 *
 * @covers \Zend\Form\View\Helper\AbstractHelper
 */
class AbstractHelperTest extends CommonTestCase
{
    public function setUp()
    {
        $this->helper = $this->getMockForAbstractClass('Zend\Form\View\Helper\AbstractHelper');

        parent::setUp();
    }

    /**
     * @group 5991
     */
    public function testWillEscapeValueAttributeValuesCorrectly()
    {
        $this->assertSame(
            'data-value="breaking&#x20;your&#x20;HTML&#x20;like&#x20;a&#x20;boss&#x21;&#x20;&#x5C;"',
            $this->helper->createAttributesString(['data-value' => 'breaking your HTML like a boss! \\'])
        );
    }

    public function testWillEncodeValueAttributeValuesCorrectly()
    {
        $escaper = new Escaper('iso-8859-1');

        $this->helper->setEncoding('iso-8859-1');

        $this->assertSame(
            'data-value="' . $escaper->escapeHtmlAttr('Título') . '"',
            $this->helper->createAttributesString(['data-value' => 'Título'])
        );
    }

    public function testWillNotEncodeValueAttributeValuesCorrectly()
    {
        $escaper = new Escaper('iso-8859-1');

        $this->assertNotSame(
            'data-value="' . $escaper->escapeHtmlAttr('Título') . '"',
            $this->helper->createAttributesString(['data-value' => 'Título'])
        );
    }

    public function testWillTranslateAttributeValuesCorrectly()
    {
        $translator = self::getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock();

        $translator
            ->expects(self::exactly(2))
            ->method('translate')
            ->with(
                self::equalTo('Welcome'),
                self::equalTo('view-helper-text-domain')
            )
            ->willReturn('Willkommen');

        $this->helper
            ->addTranslatableAttribute('data-translate-me')
            ->addTranslatableAttributePrefix('data-translatable-')
            ->setTranslatorEnabled(true)
            ->setTranslator(
                $translator,
                'view-helper-text-domain'
            );

        $this->assertSame(
            'data-translate-me="Willkommen"',
            $this->helper->createAttributesString(['data-translate-me' => 'Welcome'])
        );

        $this->assertSame(
            'data-translatable-welcome="Willkommen"',
            $this->helper->createAttributesString(['data-translatable-welcome' => 'Welcome'])
        );

        $this->assertSame(
            'class="Welcome"',
            $this->helper->createAttributesString(['class' => 'Welcome'])
        );
    }

    public function testWillTranslateDefaultAttributeValuesCorrectly()
    {
        $translator = self::getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock();

        $translator
            ->expects(self::exactly(2))
            ->method('translate')
            ->with(
                self::equalTo('Welcome'),
                self::equalTo('view-helper-text-domain')
            )
            ->willReturn('Willkommen');

        AbstractHelper::addDefaultTranslatableAttribute('data-translate-me');
        AbstractHelper::addDefaultTranslatableAttributePrefix('data-translatable-');

        $this->helper
            ->setTranslatorEnabled(true)
            ->setTranslator(
                $translator,
                'view-helper-text-domain'
            );

        $this->assertSame(
            'data-translate-me="Willkommen"',
            $this->helper->createAttributesString(['data-translate-me' => 'Welcome'])
        );

        $this->assertSame(
            'data-translatable-welcome="Willkommen"',
            $this->helper->createAttributesString(['data-translatable-welcome' => 'Welcome'])
        );

        $this->assertSame(
            'class="Welcome"',
            $this->helper->createAttributesString(['class' => 'Welcome'])
        );
    }
}

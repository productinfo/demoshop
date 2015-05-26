<?php

namespace SS6\ShopBundle\Form\Admin\Module;

use SS6\ShopBundle\Form\FormType;
use SS6\ShopBundle\Model\Module\ModuleList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModulesFormType extends AbstractType {

	const MODULES_SUBFORM_NAME = 'modules';

	/**
	 * @var \SS6\ShopBundle\Model\Module\ModuleList
	 */
	private $moduleList;

	public function __construct(ModuleList $moduleList) {
		$this->moduleList = $moduleList;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'modules_form';
	}

	/**
	 * @param \Symfony\Component\Form\FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$supportedModules = $this->moduleList->getAll();

		$builder
			->add(self::MODULES_SUBFORM_NAME, FormType::FORM)
			->add('save', FormType::SUBMIT);

		foreach ($supportedModules as $moduleName) {
			$builder->get(self::MODULES_SUBFORM_NAME)
				->add($moduleName, FormType::YES_NO);
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults([
			'attr' => ['novalidate' => 'novalidate'],
		]);
	}

}

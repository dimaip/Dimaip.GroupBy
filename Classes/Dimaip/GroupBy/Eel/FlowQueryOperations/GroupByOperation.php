<?php
namespace Dimaip\GroupBy\Eel\FlowQueryOperations;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\FlowQuery\Operations\AbstractOperation;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * GroupBy Operation
 *
 * Takes an array of things and groups it by discriminator specified as as an Eel Expression
 *
 */
class GroupByOperation extends AbstractOperation {
	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	static protected $shortName = 'groupBy';

	/**
	 * {@inheritdoc}
	 *
	 * @var integer
	 */
	static protected $priority = 100;

	/**
	 * {@inheritdoc}
	 *
	 * @var boolean
	 */
	static protected $final = TRUE;

	/**
	 * {@inheritdoc}
	 *
	 * @param array (or array-like object) $context onto which this operation should be applied
	 * @return boolean TRUE if the operation can be applied onto the $context, FALSE otherwise
	 */
	public function canEvaluate($context) {
		return (isset($context[0]) && ($context[0] instanceof NodeInterface));
	}

	/**
	 * @Flow\Inject
	 * @var \Neos\Eel\EelEvaluatorInterface
	 */
	protected $eelEvaluator;

	/**
	 * @Flow\InjectConfiguration(package="TYPO3.TypoScript", path="defaultContext")
	 * @var array
	 */
	protected $defaultContextConfiguration;

	/**
	 * @param \Neos\Eel\FlowQuery\FlowQuery $flowQuery
	 * @param array $arguments
	 * @return array
	 */
	public function evaluate(\Neos\Eel\FlowQuery\FlowQuery $flowQuery, array $arguments) {
		if (!isset($arguments[0]) || empty($arguments[0])) {
			throw new \Neos\Eel\FlowQuery\FlowQueryException('No Eel expression provided', 1332492243);
		}
		$expression = '${' . $arguments[0] . '}';
		$context = $flowQuery->getContext();
		$result = [];
		foreach ($context as $node) {
			$key = \Neos\Eel\Utility::evaluateEelExpression($expression, $this->eelEvaluator, array('node' => $node), $this->defaultContextConfiguration);
			$key = is_string($key) ? $key : '_invalid';
			$result[$key][] = $node;
		}
		ksort($result);
		return $result;
	}
}

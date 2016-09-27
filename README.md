# Dimaip.GroupBy
FlowQuery groupBy operation to group arrays by EEL discriminator

Once upon a time I had a list of names, and I needed to group them by letter.
So I decided there should be a generic way to group array of nodes into subgroups, based on EEL expression... And here it is!

## TLDR;

```
groupedNodesByFirstLetterOfLastName = ${q(site).find('[instanceof Some.Node:Type]').groupBy('String.substring(node.properties.lastName, 0, 1)')}
```

## Full example

Once you have grouped the nodes, you can render them any way you like: with Fluid (yikes!) or with Fusion (yay!).

```
prototype(Name.Space:GroupedList) < prototype(TYPO3.TypoScript:Collection) {
	@process.tmpl = ${'<div class="grouped-list">' + value + '</div>'}
	@context.nodes = ${q(site).find('[instanceof Name.Space:Person]')}
	collection = ${nodes.groupBy('String.substring(node.properties.lastName, 0, 1)')}
	itemName = 'nodes'
	itemKey = 'firstLetter'
	itemRenderer = TYPO3.TypoScript:Collection {
		@process.tmpl = ${'<div><h2>' + firstLetter + '</h2><ul>' + value + '</ul></div>'}
		collection = ${nodes}
		itemName = 'node'
		itemRenderer = ${node.properties.lastName + ' ' + node.properties.lastName}
	}
}
```

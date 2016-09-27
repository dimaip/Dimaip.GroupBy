# Dimaip.GroupBy
FlowQuery groupBy operation to group arrays by EEL discriminator

Once upon a time I had a list of persons nodes, and I needed to group them by first letter.
So I decided there should be a generic way to group array of nodes into subgroups, based on an EEL expression... And here it is!

## TLDR;

Install: `composer require dimaip/groupby`

Use:
```
groupedNodesByFirstLetterOfLastName = ${q(nodes).groupBy('String.substring(node.properties.lastName, 0, 1)')}
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

In a similar way you can group nodes based on a very complex EEL expressions, let your imagination fly!

## Credit

Initial development sponsored by Stefan Johänntgen

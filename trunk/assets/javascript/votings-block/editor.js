import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps } from '@wordpress/block-editor'
import { Placeholder } from '@wordpress/components'
import { __ } from '@wordpress/i18n'

registerBlockType('concordamos/votings', {
	category: 'theme',
	title: __('Votings', 'concordamos'),
	icon: 'privacy',
	attributes: {},
	edit ({ attributes, setAttributes }) {
		const blockProps = useBlockProps()

		return (
			<div {...blockProps}>
				<Placeholder
					icon="yes-alt"
					label={__('Votings', 'concordamos')}
					isColumnLayout={true}
				/>
			</div>
		)
	},
	save () {
		return (
			<div className="concordamos-votings-block"/>
		)
	},
})

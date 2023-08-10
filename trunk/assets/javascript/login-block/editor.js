import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps } from '@wordpress/block-editor'
import { Placeholder } from '@wordpress/components'
import { __ } from '@wordpress/i18n'

registerBlockType('concordamos/login', {
	category: 'theme',
	title: __('Login', 'concordamos'),
	icon: 'privacy',
	attributes: {},
	edit ({ attributes, setAttributes }) {
		const blockProps = useBlockProps()

		return (
			<div {...blockProps}>
				<Placeholder
					icon="privacy"
					label={__('Login', 'concordamos')}
					isColumnLayout={true}
				/>
			</div>
		)
	},
	save () {
		return null
	},
})

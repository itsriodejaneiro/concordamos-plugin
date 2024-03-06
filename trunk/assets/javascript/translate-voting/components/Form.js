import { __, sprintf } from '@wordpress/i18n'
import { useState } from 'react'

import LocaleSelector from './LocaleSelector'
import Modal, { useModal } from '../../shared/components/Modal'
import TranslationText from './TranslationText'
import { apiFetch } from '../../shared/hooks/fetch'
import { navigateTo } from '../../shared/utils/location'

function populateVotingOptions (template) {
	const options = {}
	for (const votingId of Object.keys(template.voting_options)) {
		options[votingId] = {
			option_name: '',
			option_description: '',
			option_link: '',
		}
	}
	return options
}

export default function Form() {
	const template = concordamos.translation_template

	const confirmModal = useModal(false)
	const [locale, setLocale] = useState(null)
	const [isSubmitting, setIsSubmitting] = useState(false)
	const [votingName, setVotingName] = useState('')
	const [votingDescription, setVotingDescription] = useState('')
	const [tags, setTags] = useState('')
	const [votingOptions, setVotingOptions] = useState(() => populateVotingOptions(template))

	function confirmTranslation (event) {
		event.preventDefault()
		confirmModal.open()
	}

	function handleSubmit (event) {
		event.preventDefault()

		setIsSubmitting(true)

		apiFetch('POST', 'translation/', {
			'locale'             : locale,
			'tags'               : tags,
			'user_id'            : concordamos.user_id,
			'voting_description' : votingDescription,
			'voting_id'          : concordamos.voting_id,
			'voting_name'        : votingName,
			'voting_options'     : votingOptions,
		}).then(response => {
			if (response.status === 'error') {
				throw new Error(response.message)
			} else {
				navigateTo(response.post_url)
			}
		})
	}

	function handleBack () {
		setIsSubmitting(false)
		confirmModal.close()
	}

	function setOption (optionId, field, value) {
		setVotingOptions((options) => ({
			...options,
			[optionId]: { ...options[optionId], [field]: value },
		}))
	}

	return <>
		<form onSubmit={confirmTranslation}>
			<div class="title-section">{__('General voting settings', 'concordamos')}</div>
			<LocaleSelector
				label={__('Language', 'concordamos')}
				name="locale"
				onChange={e => setLocale(e.target.value)}
				template={template}
				value={locale}
			/>
			<TranslationText
				label={__('Voting name', 'concordamos')}
				name="voting_name"
				type="textarea"
				source={template.voting_name}
				value={votingName}
				maxLength={100}
				onChange={e => setVotingName(e.target.value)}
				/>
			<TranslationText
				label={__('Voting description', 'concordamos')}
				name="voting_description"
				type="textarea"
				source={template.voting_description}
				value={votingDescription}
				maxLength={150}
				onChange={e => setVotingDescription(e.target.value)}
			/>
			<TranslationText
				label={__('Tags', 'concordamos')}
				name="tags"
				type="textarea"
				placeholder={__('Add comma-separated tags', 'concordamos')}
				source={template.tags}
				value={tags}
				maxLength={150}
				onChange={e => setTags(e.target.value)}
			/>

			<div class="title-section">{__('Options of the voting', 'concordamos')}</div>
			{Object.entries(votingOptions).map(([optionId, option], index) => {
				const sourceOption = template.voting_options[optionId]
				return <>
					<div class="title-subsection">{sprintf(__('Option %s', 'concordamos'), index + 1)}</div>
					<TranslationText
						label={__('Title of the option', 'concordamos')}
						name={`voting_options[${optionId}][option_name]`}
						type="textarea"
						source={sourceOption.option_name}
						value={option.option_name}
						onChange={e => setOption(optionId, 'option_name', e.target.value)}
					/>
					<TranslationText
						label={__('Description of the option', 'concordamos')}
						name={`voting_options[${optionId}][option_description]`}
						type="textarea"
						source={sourceOption.option_description}
						value={option.option_description}
						onChange={e => setOption(optionId, 'option_description', e.target.value)}
					/>
					<TranslationText
						label={__('Link of the option', 'concordamos')}
						name={`voting_options[${optionId}][option_link]`}
						type="url"
						source={sourceOption.option_link}
						value={option.option_link}
						onChange={e => setOption(optionId, 'option_link', e.target.value)}
					/>
				</>
			})}

			<button type="submit" class="button-full">{__('Translate voting', 'concordamos')}</button>
		</form>
		<Modal controller={confirmModal}>
			<h2>{ __('Confirm voting translation', 'concordamos') }</h2>
			<div className="buttons">
				<button type="button" className="button primary" onClick={handleSubmit} disabled={isSubmitting}>
					{isSubmitting ? __('Translating voting...', 'concordamos') : __('Translate', 'concordamos')}
				</button>
				<button type="button" className="button link" onClick={handleBack}>{__('Back', 'concordamos')}</button>
			</div>
		</Modal>
	</>
}

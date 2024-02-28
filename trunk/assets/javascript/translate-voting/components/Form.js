import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import LocaleSelector from './LocaleSelector'
import Modal, { useModal } from '../../shared/components/Modal'
import TranslationText from './TranslationText'
import { apiFetch } from '../../shared/hooks/fetch'
import { navigateTo } from '../../shared/utils/location'

export default function Form({ template }) {
	const confirmModal = useModal(false)
	const [locale, setLocale] = useState(null)
	const [isSubmitting, setIsSubmitting] = useState(false)
	const [votingName, setVotingName] = useState('')
	const [votingDescription, setVotingDescription] = useState('')

	function confirmTranslation (event) {
		event.preventDefault()
		confirmModal.open()
	}

	function handleSubmit (event) {
		event.preventDefault()

		setIsSubmitting(true)

		console.log({
			voting_description: votingDescription,
			voting_name: votingName,
		})

		setIsSubmitting(false)
	}

	function handleBack () {
		setIsSubmitting(false)
		confirmModal.close()
	}

	return <>
		<form onSubmit={confirmTranslation}>
			<div class="title-section">{__('General voting settings', 'concordamos')}</div>
			<LocaleSelector
				label={__('Language', 'concordamos')}
				name="locale"
				onChange={e => setLocale(e.target.value)}
				value={locale}
			/>
			<TranslationText
				label={__('Voting name', 'concordamos')}
				name="voting_name"
				original={template.voting_name}
				maxLength={100}
				onChange={e => setVotingName(e.target.value)}
			/>
			<TranslationText
				label={__('Voting description', 'concordamos')}
				name="voting_description"
				original={template.voting_description}
				maxLength={100}
				onChange={e => setVotingDescription(e.target.value)}
			/>

			<div class="title-section">{__('Options of the voting', 'concordamos')}</div>

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

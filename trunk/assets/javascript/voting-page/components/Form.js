import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Checkbox from './Checkbox'
import Modal, { useModal } from '../../shared/components/Modal'
import Number from './Number'
import Options from './Options'
import Radio from './Radio'
import StartEnd from './StartEnd'
import Text from './Text'
import Textarea from './Textarea'
import { navigateTo } from '../../shared/utils/location'

const Form = () => {

	const [votingType, setVotingType] = useState('public')
	const [votingAccess, setVotingAccess] = useState('no')
	const [votingName, setVotingName] = useState('')
	const [description, setDescription] = useState('')
	const [numberOfVoters, setNumberOfVoters] = useState('')
	const [votingCredits, setVotingCredits] = useState('')
	const [tags, setTags] = useState('')
	const [votingOptions, setVotingOptions] = useState([])
	const [startEndDateTime, setStartEndDateTime] = useState([])

	const confirmModal = useModal(false)

	function confirmCreation (event) {
		event.preventDefault()
		confirmModal.open()
	}

	const handleChange = (event) => {
		setVotingAccess(event.target.checked ? 'yes' : 'no')
	}

	const votingTypeOptions = {
		'public': __('Public voting', 'concordamos'),
		'private': __('Private voting', 'concordamos'),
	}

	const handleSubmit = (event) => {
		event.preventDefault()

		if (numberOfVoters <= 0) {
			alert(__('The number of voters must be greater than zero', 'concordamos'))
			return
		}

		if (votingCredits <= 0) {
			alert(__('The number of credits must be greater than zero', 'concordamos'))
			return
		}

		if (!votingType || !votingName || !description || !numberOfVoters || !votingCredits || !tags || !startEndDateTime || !votingOptions) {
			alert(__('Check empty fields', 'concordamos'))
			return
		}

		if (startEndDateTime[1] <= startEndDateTime[0]) {
			alert(__('Check start and end date', 'concordamos'))
			return
		}

		fetch(new URL('create-voting/', concordamos.rest_url), {
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce'  : concordamos.nonce
		  },
		  method: 'POST',
			body: JSON.stringify({
				'user_id'           : concordamos.user_id,
				'voting_type'       : votingType,
				'voting_access'     : votingAccess,
				'voting_name'       : votingName,
				'voting_description': description,
				'number_voters'     : numberOfVoters,
				'credits_voter'     : votingCredits,
				'tags'              : tags,
				'date_start'        : startEndDateTime[0],
				'date_end'          : startEndDateTime[1],
				'voting_options'    : votingOptions
			})
		})
		.then(response => response.json())
		.then(response => {
			if (response.status === 'error') {
				throw new Error(response.message)
			} else {
				navigateTo(response.post_url)
			}
		})
		.catch(error => console.error(error))
	}

	return (
		<>
			<form onSubmit={confirmCreation}>
				<Radio
					defaultValue={votingType}
					label={__('Type of the voting', 'concordamos')}
					name="voting_type"
					onChange={e => setVotingType(e.target.value)}
					options={votingTypeOptions}
					titleCssClass="title-section"
				/>

				<span className="title-section">{__('General voting settings', 'concordamos')}</span>
				<p>{__('Enter the name, description, number of voters, credits and tags for the poll', 'concordamos')}</p>

				<Checkbox
					label={__('Request login to vote', 'concordamos')}
					name="voting_access"
					onChange={e => handleChange(e)}
				/>
				<Text
					label={__('Voting name', 'concordamos')}
					name="voting_name"
					placeholder={__('Give the voting a name', 'concordamos')}
					onChange={e => setVotingName(e.target.value)}
				/>
				<Textarea
					label={__('Voting description', 'concordamos')}
					name="voting_description"
					placeholder={__('Describe voting details', 'concordamos')}
					onChange={e => setDescription(e.target.value)}
				/>
				<Number
					label={__('Number of voters', 'concordamos')}
					name="number_voters"
					placeholder={__('How many voting links would you like to generate?', 'concordamos')}
					onChange={e => setNumberOfVoters(e.target.value)}
				/>
				<Number
					label={__('Voting credits per voter', 'concordamos')}
					name="credits_voter"
					placeholder={__('How many votes will each voter receive?', 'concordamos')}
					onChange={e => setVotingCredits(e.target.value)}
				/>
				<Textarea
					label={__('Tags', 'concordamos')}
					name="tags"
					placeholder={__('Add comma-separated tags', 'concordamos')}
					onChange={e => setTags(e.target.value)}
				/>
				<StartEnd
					label={__('Duration of the voting', 'concordamos')}
					description={__('Once the voting period has started, the vote cannot be deleted. You can change the duration of the poll later', 'concordamos')}
					setStartEndDateTime={setStartEndDateTime}
				/>
				<Options
					label={__('Options of the voting', 'concordamos')}
					description={__('Enter your poll options (add at least 2 options to advance)', 'concordamos')}
					name="voting_options"
					value={votingOptions}
					setVotingOptions={setVotingOptions}
				/>

				{
					(votingOptions.length >= 2)
					? <button type="submit" class="button-full">{__('Submit vote', 'concordamos')}</button>
					: <button type="button" class="button-full disabled">{__('Add at least two votes', 'concordamos')}</button>
				}
			</form>
			<Modal controller={confirmModal}>
				<h2>{ __('Confirm voting creation', 'concordamos') }</h2>
				<p dangerouslySetInnerHTML={ { __html: __('After creation the voting, you can <strong>only</strong> change its <strong>start</strong> and <strong>end dates</strong>.', 'concordamos') } }/>
				<div className="buttons">
					<button type="button" className="button primary" onClick={handleSubmit}>{__('Create voting', 'concordamos')}</button>
					<button type="button" className="button link" onClick={confirmModal.close}>{__('Cancel', 'concordamos')}</button>
				</div>
			</Modal>
		</>
	)
}

export default Form

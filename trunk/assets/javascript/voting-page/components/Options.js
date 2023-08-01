import { __, sprintf } from '@wordpress/i18n'
import React, { useState, useEffect } from 'react'

const Options = ({label, description, setVotingOptions}) => {

	const [inputFields, setInputFields] = useState([
		{option_name: '', option_description: '', option_link: ''}
	])

	const [toggleStates, setToggleStates] = useState(inputFields.map(() => true))

	const handleFormChange = (index, event) => {
		let data = [...inputFields]
		data[index][event.target.name] = event.target.value
		setInputFields(data)
	}

	const handleToggle = (index) => {
		setToggleStates(toggleStates.map((value, i) => i === index ? true : false))
	};

	const addFields = () => {
		let newfield = {option_name: '', option_description: '', option_link: ''}
		setInputFields([...inputFields, newfield])
		setToggleStates([...Array(inputFields.length).fill(false), true])
	}

	const removeFields = (index) => {
		let data = [...inputFields]
		data.splice(index, 1)
		setInputFields(data)

		let newToggleStates = [...toggleStates]
		let isOpen = newToggleStates[index]
		newToggleStates.splice(index, 1)

		if (isOpen && newToggleStates.length > 0) {
			if (index > 0) {
				newToggleStates[index - 1] = true
			} else {
				newToggleStates[0] = true
			}
		}

		setToggleStates(newToggleStates)
	}

	const removeEmptyObjects = (arr) => {
		return arr.filter(function(item) {
			return item.option_name !== "" || item.option_description !== "" || item.option_link !== ""
		});
	}

	useEffect(() => {
		setVotingOptions(removeEmptyObjects(inputFields))
	}, [inputFields])

	return (
		<>
			<div className="field field-options">
				<span className="title-section">{label}</span>
				{
					description
					? <span className="description-section">{description}</span>
					: null
				}

				<div className="fields">
					{inputFields.map((input, index) => {
						return (
							<div className={`option ${toggleStates[index] ? "open" : ""}`} key={index}>

								{
									! toggleStates[index]
									? <div className="option-header">

										{
											inputFields[index].option_name
											? <span>{inputFields[index].option_name}</span>
											: <span>Opção {index + 1}</span>
										}

										<div className="buttons">
											<button className="edit" type="button" onClick={() => handleToggle(index)}>{__('Edit', 'concordamos')}</button>

											{
												inputFields.length >= 2
												? <button className="trash" type="button" onClick={() => removeFields(index)}>{__('Delete', 'concordamos')}</button>
												: null
											}
										</div>
									</div>
									: null
								}

								<div className="option-content">
									<label>
										{
											inputFields.length >= 2
											? <span>{sprintf(__('Option %s', 'concordamos'), index + 1)} <button className="trash" type="button" onClick={() => removeFields(index)}>{__('Delete', 'concordamos')}</button></span>
											: <span>{sprintf(__('Option %s', 'concordamos'), index + 1)}</span>
										}

										<input
											name='option_name'
											placeholder={__('Title of the option', 'concordamos')}
											type='text'
											value={input.option_name}
											onChange={event => handleFormChange(index, event)}
										/>
									</label>

									<label>
										<span>{sprintf(__('Description of the option %s', 'concordamos'), index + 1)}</span>
										<input
											name='option_description'
											placeholder={__('Description of the option', 'concordamos')}
											type='text'
											value={input.option_description}
											onChange={e => handleFormChange(index, e)}
										/>
									</label>

									<label>
										<span>{sprintf(__('Link of the option %s', 'concordamos'), index + 1)}</span>
										<input
											name='option_link'
											placeholder='www.example.com'
											type='text'
											value={input.option_link}
											onChange={e => handleFormChange(index, e)}
										/>
									</label>
								</div>
							</div>
						)
					})}
				</div>

				<button type="button" class="button-full" onClick={addFields}>+ {__('Add option', 'concordamos')}</button>
			</div>
		</>
	)
}

export default Options

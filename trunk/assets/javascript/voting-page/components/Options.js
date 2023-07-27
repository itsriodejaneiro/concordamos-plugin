import React, { useState, useEffect } from "react";

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
											<button className="edit" type="button" onClick={() => handleToggle(index)}>Editar</button>

											{
												inputFields.length >= 2
												? <button className="trash" type="button" onClick={() => removeFields(index)}>Delete</button>
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
											? <span>Opção {index + 1} <button className="trash" type="button" onClick={() => removeFields(index)}>Delete</button></span>
											: <span>Opção {index + 1}</span>
										}

										<input
											name='option_name'
											placeholder='Title of the option'
											type='text'
											value={input.option_name}
											onChange={event => handleFormChange(index, event)}
										/>
									</label>

									<label>
										<span>Descrição da opção {index + 1}</span>
										<input
											name='option_description'
											placeholder='Description of the option'
											type='text'
											value={input.option_description}
											onChange={e => handleFormChange(index, e)}
										/>
									</label>

									<label>
										<span>Link da opção {index + 1}</span>
										<input
											name='option_link'
											placeholder='www.example.com.br'
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

				<button type="button" class="button-full" onClick={addFields}>+ Add option</button>
			</div>
		</>
	)
}

export default Options

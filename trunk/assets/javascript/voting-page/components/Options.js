import React, { useState, useEffect } from "react";

const Options = ({label, setVotingOptions}) => {

	const [inputFields, setInputFields] = useState([
		{option_name: '', option_description: '', option_link: ''}
	])

	const handleFormChange = (index, event) => {
		let data = [...inputFields];
		data[index][event.target.name] = event.target.value;
		setInputFields(data);
	}

	const addFields = () => {
		let newfield = {option_name: '', option_description: '', option_link: ''}
		setInputFields([...inputFields, newfield])
	}

	const removeFields = (index) => {
		let data = [...inputFields];
		data.splice(index, 1)
		setInputFields(data)
	}

	const removeEmptyObjects = (arr) => {
		return arr.filter(function(item) {
			return item.option_name !== "" || item.option_description !== "" || item.option_link !== "";
		});
	}

	useEffect(() => {
		setVotingOptions(removeEmptyObjects(inputFields))
	}, [inputFields])

	return (
		<>
			<div className="field field-options">

				<div className="fields">
					<span className="label">{label}</span>

					{inputFields.map((input, index) => {
						return (
							<div key={index}>
								<label>

									{
										(inputFields.length >= 2)
										? <span>Opção {index + 1} <button type="button" onClick={() => removeFields(index)}>Remover opção</button></span>
										: <span>Opção {index + 1}</span>
									}


									<input
										name='option_name'
										placeholder='Título da opção'
										value={input.option_name}
										onChange={event => handleFormChange(index, event)}
									/>
								</label>

								<label>
									<span>Descrição da opção {index + 1}</span>
									<input
										name='option_description'
										placeholder='Descrição da opção 1'
										value={input.option_description}
										onChange={e => handleFormChange(index, e)}
									/>
								</label>

								<label>
									<span>Link da opção {index + 1}</span>
									<input
										name='option_link'
										placeholder='www.com.br'
										value={input.option_link}
										onChange={e => handleFormChange(index, e)}
									/>
								</label>
							</div>
						)
					})}
				</div>

				<button type="button" onClick={addFields}>Adicionar opção</button>
			</div>
		</>
	)
}

export default Options

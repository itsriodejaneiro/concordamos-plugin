import { useState } from "react"
import Radio from "./Radio";
import Text from "./Text";
import Textarea from "./Textarea";
import Number from "./Number";
import Hidden from "./Hidden";

const Form = () => {

	const [votingType, setVotingType] = useState("public");
	const [votingName, setVotingName] = useState("");
	const [description, setDescription] = useState("");
	const [numberOfVoters, setNumberOfVoters] = useState("");
	const [votingCredits, setVotingCredits] = useState("");
	const [tags, setTags] = useState("");
	const [options, setOptions] = useState([]);

	const baseUrl = window.location.origin + '/wp-json/concordamos/v1/create-voting/'

	// voting_type options
	const votingTypeOptions = {
		'public': 'Votação Pública',
		'private': 'Votação Privada'
	}

	const handleSubmit = (event) => {
		event.preventDefault();

		if (!votingType || !votingName || !description || !numberOfVoters || !votingCredits || !tags) {
			alert('Please, check empty fields');
			return;
		}

		fetch(baseUrl, {
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce'  : concordamos.nonce
		  },
		  method: 'POST',
			body: JSON.stringify({
				"user_id"           : concordamos.user_id,
				"voting_type"       : votingType,
				"voting_name"       : votingName,
				"voting_description": description,
				"number_voters"     : numberOfVoters,
				"credits_voter"     : votingCredits,
				"tags"              : tags,
				"options_voting"    : {
					"option1": "Opção 1",
					"option2": "Opção 2"
				}
			})
		})
		.then(response => response.json())
		.then(response => {
			if (response.status === 'error') {
				throw new Error(response.message);
			} else {
				console.log(response);
			}
		})
		.catch(error => console.error('Error:', error));
	};

	return (
		<>
			<form onSubmit={handleSubmit}>
				<Radio
					defaultValue={votingType}
					label="Tipo de votação"
					name="voting_type"
					options={votingTypeOptions}
					onChange={e => setVotingType(e.target.value)}
				/>
				<Text
					label="Voting name"
					name="voting_name"
					placeholder="Give the event a name"
					onChange={e => setVotingName(e.target.value)}
				/>
				<Textarea
					label="Description of the voting"
					name="voting_description"
					placeholder="Describe event details"
					onChange={e => setDescription(e.target.value)}
				/>
				<Number
					label="Number of voters"
					name="number_voters"
					placeholder="How many voting links would you like to generate?"
					onChange={e => setNumberOfVoters(e.target.value)}
				/>
				<Number
					label="Voting credits per voter"
					name="credits_voter"
					placeholder="How many votes will each voter receive?"
					onChange={e => setVotingCredits(e.target.value)}
				/>
				<Textarea
					label="Tags"
					name="tags"
					placeholder="Add comma separated tags"
					onChange={e => setTags(e.target.value)}
				/>
				<Hidden
					name="voting_options"
					onChange={e => setOptions(e.target.value)}
				/>
				<button type="submit">Enviar</button>
			</form>
		</>
	)
}

export default Form

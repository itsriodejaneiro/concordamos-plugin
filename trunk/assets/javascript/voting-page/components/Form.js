import { useState } from "react"
import Checkbox from "./Checkbox";
import Number from "./Number";
import Options from "./Options";
import Radio from "./Radio";
import StartEnd from "./StartEnd";
import Text from "./Text";
import Textarea from "./Textarea";

const Form = () => {

	const [votingType, setVotingType] = useState("public");
	const [votingAccess, setVotingAccess] = useState("no");
	const [votingName, setVotingName] = useState("");
	const [description, setDescription] = useState("");
	const [numberOfVoters, setNumberOfVoters] = useState("");
	const [votingCredits, setVotingCredits] = useState("");
	const [tags, setTags] = useState("");
	const [votingOptions, setVotingOptions] = useState([]);
	const [startEndDateTime, setStartEndDateTime] = useState([]);

	const handleChange = (event) => {
		setVotingAccess(event.target.checked ? "yes" : "no");
	};

	const baseUrl = window.location.origin + '/wp-json/concordamos/v1/create-voting/'

	// voting_type options
	const votingTypeOptions = {
		'public': 'Public voting',
		'private': 'Private voting'
	}

	// voting_access options
	const votingAccessOptions = {
		'yes': 'Yes',
		'no': 'No'
	}

	const handleSubmit = (event) => {
		event.preventDefault();

		if (numberOfVoters <= 0) {
			alert('The number of voters must be greater than zero');
			return;
		}

		if (votingCredits <= 0) {
			alert('The number of credits must be greater than zero');
			return;
		}

		if (!votingType || !votingName || !description || !numberOfVoters || !votingCredits || !tags || !startEndDateTime || !votingOptions) {
			alert('Check empty fields');
			return;
		}

		if (startEndDateTime[1] <= startEndDateTime[0]) {
			alert('Check start and end date');
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
				"voting_access"     : votingAccess,
				"voting_name"       : votingName,
				"voting_description": description,
				"number_voters"     : numberOfVoters,
				"credits_voter"     : votingCredits,
				"tags"              : tags,
				"date_start"        : startEndDateTime[0],
				"date_end"          : startEndDateTime[1],
				"voting_options"    : votingOptions
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
					label="Type of the voting"
					name="voting_type"
					onChange={e => setVotingType(e.target.value)}
					options={votingTypeOptions}
					titleCssClass="title-section"
				/>

				<span className="title-section">General voting settings</span>
				<p>Enter the name, description, number of voters, credits and tags for the poll</p>

				<Checkbox
					label="Request login to vote"
					name="voting_access"
					onChange={e => handleChange(e)}
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
				<StartEnd
					label="Duration of the event"
					description="Once the voting period has started, the vote cannot be deleted. You can change the duration of the poll later"
					setStartEndDateTime={setStartEndDateTime}
				/>
				<Options
					label="Options of the voting"
					description="Enter your poll options (add at least 2 options to advance)"
					name="voting_options"
					value={votingOptions}
					setVotingOptions={setVotingOptions}
				/>

				{
					(votingOptions.length >= 2)
					? <button type="submit" class="button-full">Send vote</button>
					: <button type="button" class="button-full disabled">Add at least two options</button>
				}
			</form>
		</>
	)
}

export default Form

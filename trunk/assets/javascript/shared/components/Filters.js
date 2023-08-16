import { __, _x } from '@wordpress/i18n'

import Radio from '../../voting-archive/components/Radio'

const votingAccessOptions = {
	'yes': __('With login', 'concordamos'),
	'no': __('No login', 'concordamos'),
}

const votingTimeOptions = {
	'past': _x('Open', 'votings', 'concordamos'),
	'present': _x('Concluded', 'votings', 'concordamos'),
	
}

export default function Filters ({ filters, onChange }) {
	function innerSetFilters (key) {
		return function (value) {
			return onChange({ ...filters, [key]: value })
		}
	}
 
	return (
		<details>
			<summary>{__('Filters', 'concordamos')}</summary>
				<div class="filters-open">
					<Radio name="time" options={votingTimeOptions} value={filters.time} onChange={innerSetFilters('time')}/>
					<Radio name="access" options={votingAccessOptions} value={filters.access} onChange={innerSetFilters('access')}/>
				</div>
		</details>
	)
}

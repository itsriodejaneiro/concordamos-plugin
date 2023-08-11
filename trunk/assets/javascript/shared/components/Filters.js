import { __, _x } from '@wordpress/i18n'

import Radio from '../../voting-archive/components/Radio'

const votingAccessOptions = {
	'yes': __('Yes', 'concordamos'),
	'no': __('No', 'concordamos'),
	'': _x('All', 'accesses', 'concordamos'),
}

const votingTimeOptions = {
	'past': _x('Concluded', 'votings', 'concordamos'),
	'present': _x('Open', 'votings', 'concordamos'),
	'future': _x('Scheduled', 'votings', 'concordamos'),
	'': _x('All', 'votings', 'concordamos'),
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
					<div class="filter-label">{__('Voting status', 'concordamos')}</div>
					<Radio name="time" options={votingTimeOptions} value={filters.time} onChange={innerSetFilters('time')}/>
					<div class="filter-label">{__('Does it require login?', 'concordamos')}</div>
					<Radio name="access" options={votingAccessOptions} value={filters.access} onChange={innerSetFilters('access')}/>
				</div>
		</details>
	)
}

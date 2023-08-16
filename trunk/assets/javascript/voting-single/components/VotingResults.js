import { __ } from '@wordpress/i18n'
import { useMemo } from 'react'
import { useFetch } from '../../shared/hooks/fetch'

import { Chart as ChartJS, ArcElement, BarElement, CategoryScale, Legend, LinearScale, LineElement, PointElement, Tooltip } from "chart.js";
ChartJS.register( ArcElement, BarElement, CategoryScale, Legend, LinearScale, LineElement, PointElement, Tooltip );

import { Bar } from 'react-chartjs-2';

export default function VotingResults () {
	const { data } = useFetch(`votes/?v_id=${concordamos.v_id}`)

	const votingInfo = useMemo(() => {
		if (!data) {
			return null;
		}

		return {
			numberVoters: data.number_voters,
			participants: data.participants,
			totalCredits: data.total_credits,
			usedCredits: data.used_credits
		}
	}, [data])

	const chartData = useMemo(() => {
		if (!data) {
			return null;
		}

		return {
			labels: data.labels,
			datasets: [
				{
					label: 'Vores',
					data: data.dataset,
					backgroundColor: [
						'#26A9F9',
						'#EDE103',
						'#D41F29',
						'#F5501E',
						'#FFE4DC',
						'#FFE8BD',
						'#0E1725'
					],
					borderWidth: 0
				}
			],
		}
	}, [data])

	const chartOptions = {
		indexAxis: 'y',
		plugins: {
			legend: {
				display: false
			}
		}
	}

	return (
		<div className="wrapper voting-results">
			<div className="content">
				{ votingInfo ? (
					<div className="voting-info">
						<span>Participants voting { votingInfo.participants } / { votingInfo.numberVoters }</span>
						<span>Distributed credits { votingInfo.usedCredits } / { votingInfo.totalCredits }</span>
					</div>
				) : null }

				<div className="voting-links-grid">
					<div className="voting-links-column">
						{ chartData ? (
							<Bar options={chartOptions} data={chartData} />
						) : null }
					</div>
				</div>
			</div>
		</div>
	)
}

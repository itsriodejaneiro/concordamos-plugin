import { __ } from '@wordpress/i18n'
import { useMemo } from 'react'
import { useFetch } from '../../shared/hooks/fetch'

import { Chart as ChartJS, ArcElement, BarElement, CategoryScale, Legend, LinearScale, LineElement, PointElement, Tooltip } from "chart.js";
ChartJS.register( ArcElement, BarElement, CategoryScale, Legend, LinearScale, LineElement, PointElement, Tooltip );

import { Bar } from 'react-chartjs-2';

function trimLabels (isMobile) {
	return function (label) {
		if (!isMobile) {
			return label;
		}
		return label.length > 15 ? (label.slice(0, 20).trim() + '…') : label;
	}
}

export default function VotingResults () {
	const { data } = useFetch(`votes/?v_id=${concordamos.v_id}`);

	const isMobile = window.matchMedia('(max-width: 768px)').matches;

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
			labels: data.labels.map(trimLabels(isMobile)),
			datasets: [
				{
					label: __('Votes', 'concordamos'),
					data: data.dataset,
					yAxisID: 'y1',
					backgroundColor: '#EDE103',
					borderWidth: 0,
				},
				{
					label: __('Percents', 'concordamos'),
					data: data.dataset_percentage,
					yAxisID: 'y2',
					backgroundColor: '#26A9F9',
					borderWidth: 0,
				},
			],
		}
	}, [data])

	const chartOptions = {
		aspectRatio: isMobile ? 1 : 2,
		scales: {
			y1: {
				type: 'linear',
				display: true,
				position: 'left',
			},
			y2: {
				type: 'linear',
				display: true,
				position: 'right',

				grid: {
					drawOnChartArea: false
				},
			},
		},
		plugins: {
			legend: {
				display: true
			}
		}
	}

	return (
		<div className="wrapper voting-results">
			<div className="content">
				{ votingInfo ? (
					<div className="voting-info">
						<span>{__('Participants voting', 'concordamos')} { votingInfo.participants } / { votingInfo.numberVoters }</span>
						<span>{__('Distributed credits', 'concordamos')} { votingInfo.usedCredits } / { votingInfo.totalCredits }</span>
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

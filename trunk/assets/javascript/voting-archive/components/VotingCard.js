import Image from './Image'

const dateFormatter = new Intl.DateTimeFormat('pt-br', { dateStyle: 'short' })

function formatDate (timestamp) {
	return dateFormatter.format(timestamp)
}

function getVotingStatus (voting) {
	switch (voting.time) {
		case 'past':
			return 'Concluída'
		case 'present':
			return 'Aberta'
		case 'future':
			return 'Agendada'
	}
}

function getVotingDate (voting) {
	switch (voting.time) {
		case 'past':
			return `Desde o dia ${formatDate(voting.meta.date_end)}`
		case 'present':
			return `Até o dia ${formatDate(voting.meta.date_end)}`
		case 'future':
			return `A partir do dia ${formatDate(voting.meta.date_start)}`
	}
}

export default function VotingCard ({ voting }) {
	const isOpen = voting.time === 'present'
	const requiresLogin = voting.access === 'yes'

	return (
		<article className={`voting-card ${isOpen ? 'open' : 'closed'}`}>
			<header className={`${isOpen ? 'open' : 'closed'}`}>
				<span>{getVotingStatus(voting)}</span>
				<span>{getVotingDate(voting)}</span>
			</header>
			<main>
				<h2><a href={voting.permalink}>{voting.title}</a></h2>
				<p>{voting.content}</p>
				<p><Image src={requiresLogin ? 'private.svg' : 'public.svg'}/> {requiresLogin ? (isOpen ? 'Login necessário' : 'Login necessário para acessar os resultados') : 'Sem login'}</p>
				<ul className="voting-card__tags">
				{voting.tags.map((tag) => (
					<li key={tag.ID}>#{tag.name}</li>
				))}
				</ul>
			</main>
		</article>
	)
}

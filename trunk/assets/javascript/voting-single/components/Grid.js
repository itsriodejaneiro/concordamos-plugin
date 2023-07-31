export default function Grid ({ squares, consumed = squares }) {
	const rows = Math.ceil(Math.sqrt(squares))

	return (
		<>
			<p className="sr-only">{squares}</p>
			<div className="option-voting-grid" style={{ '--grid-rows': rows }} aria-hidden="true">
				{Array.from({ length: squares }).map((_, i) => (
					<div className={`option-voting-grid__cell ${(consumed > i ? 'consumed' : '')}`} key={i}/>
				))}
			</div>
		</>
	)
}

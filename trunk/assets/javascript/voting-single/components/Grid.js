function range (length) {
	return [...Array(length).keys()]
}

const Grid = ({ count }) => {
	const normalizedCount = Math.abs(count)
	const rows = range(normalizedCount)

	return (
		<>
			<p className="sr-only">{count}</p>
			<div className="option-voting-grid" style={{ '--grid-rows': normalizedCount }} aria-hidden="true">
				{rows.map((i) => (
					<div className="option-voting-grid__row" key={i}>
						{rows.map((j) => (
							<div className="option-voting-grid__cell" key={j}/>
						))}
					</div>
				))}
			</div>
		</>
	)
}

export default Grid

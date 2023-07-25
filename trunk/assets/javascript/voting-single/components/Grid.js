const Grid = ({ count }) => {
	const squares = count ** 2

	return (
		<>
			<p className="sr-only">{count}</p>
			<div className="option-voting-grid" style={{ '--grid-rows': Math.abs(count) }} aria-hidden="true">
				{Array.from({ length: squares }).map((_, i) => (
					<div className="option-voting-grid__cell" key={i}/>
				))}
			</div>
		</>
	)
}

export default Grid

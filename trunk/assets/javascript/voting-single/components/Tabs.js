import classNames from 'classnames'

export function Tabs ({ onChange, tab, tabs }) {
	function selectTab (newTab) {
		return function (event) {
			onChange(newTab)
		}
	}

	return (
		<div className="panel-tabs">
			{Object.entries(tabs).map(([key, label]) => (
				<button type="button" className={classNames('panel-tab', { 'selected': tab === key })} key={key} onClick={selectTab(key)}>{label}</button>
			))}
		</div>
	)
}

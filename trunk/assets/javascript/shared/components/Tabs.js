import { __ } from '@wordpress/i18n'
import classNames from 'classnames'

export default function Tabs ({ onChange, tabs, value }) {
	function selectTab (newTab) {
		return function (event) {
			onChange(newTab.id)
		}
	}

	const tabIndex = tabs.findIndex((tab) => tab.id === value)
	const previousTab = tabs[tabIndex - 1]
	const nextTab = tabs[tabIndex + 1]

	return (
		<div className="panel-tabs">
			<button type="button" className={classNames('panel-previous-tab', { 'disabled': !previousTab })} key="__previous__" onClick={selectTab(previousTab)} disabled={!previousTab}>
				<img src={concordamos.plugin_url + '/assets/images/arrow.svg'} alt={__('Previous tab', 'concordamos')}/>
			</button>
			{tabs.map((tab) => (
				<button type="button" className={classNames('panel-tab', { 'selected': tab.id === value })} key={tab.id} onClick={selectTab(tab)}>
					{tab.label}
				</button>
			))}
			<button type="button" className={classNames('panel-next-tab', { 'disabled': !nextTab })} key="__next__" onClick={selectTab(nextTab)} disabled={!nextTab}>
				<img src={concordamos.plugin_url + '/assets/images/arrow.svg'} alt={__('Next tab', 'concordamos')}/>
			</button>
		</div>
	)
}

import { __ } from '@wordpress/i18n'
import classNames from 'classnames'

export default function Tabs ({ onChange, tabs, value }) {
	function selectTab (newTab) {
		return function (event) {
			onChange(newTab.id)
		}
	}

	const tabIndex = tabs.findIndex((tab) => tab.id === value)
	const havePrevious = tabIndex > 0
	const haveNext = tabIndex < tabs.length - 1

	return (
		<div className="panel-tabs">
			<button type="button" className={classNames('panel-previous-tab', { 'disabled': !havePrevious })} key="__previous__" onClick={selectTab(tabs[tabIndex - 1])} disabled={!havePrevious}>
				<img src={concordamos.plugin_url + '/assets/images/arrow.svg'} alt={__('Previous tab', 'concordamos')}/>
			</button>
			{tabs.map((tab) => (
				<button type="button" className={classNames('panel-tab', { 'selected': tab.id === value })} key={tab.id} onClick={selectTab(tab)}>
					{tab.label}
				</button>
			))}
			<button type="button" className={classNames('panel-next-tab', { 'disabled': !haveNext })} key="__next__" onClick={selectTab(tabs[tabIndex + 1])} disabled={!haveNext}>
				<img src={concordamos.plugin_url + '/assets/images/arrow.svg'} alt={__('Next tab', 'concordamos')}/>
			</button>
		</div>
	)
}

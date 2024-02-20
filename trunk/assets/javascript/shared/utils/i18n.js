const locale = concordamos.locale.replace('_', '-')
const dateFormatter = new Intl.DateTimeFormat(locale, { day: '2-digit', month: '2-digit', year: 'numeric' })
const timeFormatter = new Intl.DateTimeFormat(locale, { hour: '2-digit', minute: '2-digit' })

export function formatDate (date) {
	return dateFormatter.format(date)
}

export function formatTime (date) {
	return timeFormatter.format(date)
}

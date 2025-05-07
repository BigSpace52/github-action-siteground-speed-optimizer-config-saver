<?php

/**
 * Functions.
 */
function escape_sequence( $code ) {
	return "\e[" . $code . 'm';
}

function format_command( $value ) {
	return escape_sequence( '36' ) . $value . escape_sequence( '0' );
}

function format_error( $value ) {
	return escape_sequence( '31' ) . escape_sequence( '1' ) . 'Error:' . escape_sequence( '0' ) . ' ' . $value;
}

function run_command( $command, $expected_result_code = 0 ) {
	echo format_command( $command ), PHP_EOL;

	passthru( $command, $result_code );

	if ( null !== $expected_result_code && $expected_result_code !== $result_code ) {
		exit( $result_code );
	}

	return $result_code;
}

/**
 * Setup.
 */
$options = getopt(
	'',
	[
		'file:',
		'settings:',
	]
);

$file = $options['file'];

$settings_result = $options['settings'];

$position_newline = strpos( $settings_result, "\n" );

if ( false !== $position_newline ) {
	$settings_result = substr( $settings_result, $position_newline );
}

$settings_hash = $settings_result;

$settings_json = base64_decode( $settings_hash );

$settings_object = json_decode( $settings_json );

$directory = dirname( $file );

if ( ! is_dir( $directory ) ) {
	mkdir( $directory, 0777, true );
}

file_put_contents(
	$file,
	json_encode( $settings_object, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES )
);

/**
 * Check Git status.
 */
$status = trim( shell_exec( 'git status --porcelain' ) );

if ( '' === $status ) {
	echo 'No changes';

	exit( 0 );
}

/**
 * GitHub CLI.
 * 
 * @link https://cli.github.com/
 */
run_command( 'gh auth status' );

/**
 * Git.
 */
$date = date( 'Y-m-d' );

$branch = "pronamic-siteground-speed-optimizer-settings/$date";

$pr_title = "SiteGround Speed Optimizer settings update ($date)";

$pr_body = "Automated backup of SiteGround Speed Optimizer settings on $date.";

run_command( 'git config user.name "pronamic-siteground-speed-optimizer-settings[bot]"' );
run_command( 'git config user.email "pronamic-siteground-speed-optimizer-settings[bot]@users.noreply.github.com"' );

run_command( "git checkout -b $branch" );

run_command( 'git add .' );

run_command( "git commit -m '$pr_title'" );

run_command( "git push origin $branch" );

/**
 * GitHub PR create.
 * 
 * @link https://cli.github.com/manual/gh_pr_create
 */
$command = <<<EOT
gh pr create \
	--title "$pr_title" \
	--body "$pr_body"
EOT;

run_command( $command );

run_command( 'gh pr merge --admin --merge --delete-branch' );

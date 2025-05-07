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

function run_shell_exec( $command ) {
	echo format_command( $command ), PHP_EOL;

    return shell_exec( $command );
}

function start_group( $name ) {
	echo '::group::', $name, PHP_EOL;
}

function end_group() {
	echo '::endgroup::', PHP_EOL;
}

/**
 * Get input.
 * 
 * @link https://docs.github.com/en/actions/creating-actions/metadata-syntax-for-github-actions#inputs
 * @link https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions#jobsjob_idstepswith
 * @link https://github.com/actions/checkout/blob/cd7d8d697e10461458bc61a30d094dc601a8b017/dist/index.js#L2699-L2717
 * @param string $name
 * @return string|array|false
 */
function get_input( $name ) {
	$env_name = 'INPUT_' . strtoupper( $name );

	return getenv( $env_name );
}

function get_required_input( $name ) {
	$value = get_input( $name );

	if ( false === $value || '' === $value ) {
		echo format_error( escape_sequence( '90' ) . 'Input required and not supplied:' . escape_sequence( '0' ) . ' ' . $name );

		exit( 1 );
	}

	return $value;
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
    $settings_result = substr( $settings_result, 0, $position_newline );
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

var_dump( $settings_object );

exit;

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
$timestamp = date( 'Y-m-d H:i' );

$pr_title = "SiteGround Speed Optimizer settings update – $timestamp";

$command = <<<EOT
gh pr create \
	--title "$pr_title" \
	--body "$pr_body"
EOT;

run_command( $command );

run_command( 'gh pr merge --admin --merge --delete-branch' );

const { __ } = wp.i18n;
const { BlockControls, InspectorControls } = wp.editor;
const { PanelBody, TextControl, Dropdown, RangeControl, ToggleControl, Toolbar, IconButton } = wp.components;

const ControlsSettings = ({ attributes, setAttributes }) => {
  const { width, autoplay, muted, source, poster, videoURL, download } = attributes;

  return (
    <div>
    <InspectorControls>
		 			<PanelBody title={ __( 'Video Settings' ) }>
            <TextControl
            label="Video URL"
            value={ videoURL }
            onChange={ ( value ) => setAttributes( { videoURL: value } ) }
          />
          <TextControl
            label="Cover URL"
            value={ poster }
            onChange={ ( value ) => setAttributes( { poster: value } ) }
          />
          <RangeControl
            label="Width"
            value={ width.number }
            onChange={ ( value ) => {
              setAttributes( { width: { number: value } } );
              // setPlayerWidth( value );
            } }
            beforeIcon="%"
            min={ 0 }
            max={ 100 }
            allowReset
          />
          <ToggleControl
            label="Download"
            checked={ download }
            onChange={ () => setAttributes( { download: ! download } ) }
          />
          <ToggleControl
            label="Auto Play"
            checked={ autoplay }
            onChange={ () => setAttributes( { autoplay: ! autoplay } ) }
          />
          <ToggleControl
            label="Muted"
            checked={ muted }
            onChange={ () => setAttributes( { muted: ! muted } ) }
          />
      </PanelBody>
	 </InspectorControls>
   <BlockControls>
  <Toolbar label="Options">

   <Dropdown
        renderToggle={ ( { isOpen, onToggle } ) => (
            <IconButton
                icon="edit"
                label="Change URL"
                onClick={ onToggle }
                aria-expanded={ isOpen }
            />
        ) }
        renderContent={ () => {
         return (<div style={ { 'min-width': '200px' } }>
             <TextControl
                  label="Video URL"
                  value={ videoURL }
                  onChange={ ( value ) => setAttributes( { videoURL: value } ) }
            />
                <TextControl
                  label="Cover URL"
                  value={ poster }
                  onChange={ ( value ) => setAttributes( { poster: value } ) }
                />
          </div>)
        } }
    />
        </Toolbar>
 </BlockControls>
 </div>
  );
};

export default ControlsSettings;

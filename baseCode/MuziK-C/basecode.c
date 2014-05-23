/*Please retain the following lines in all versions of your code*/	
	/*Welcome to K++ Think Open in Kurukshetra 2009*/
	/*Visit kplusplus.kurukshetra.org.in for updates and information*/
	/*This is the simple command line audio player that can play OGG/Vorbis audio files*/
	/*Credits are due to the GStreamer Project Team and to the authors of GStreamer Application Development Manual*/
	/*Care to give this program a name?*/

	/*Extra Header information goes here*/
		
	/*Extra Header information ends here*/
	/*Add Credits to other authors here(if any)*/
	
	/*Credits to other authors ends here*/
/*Thank you for retaining this header information. You can add extra header information in the space provided*/

/*Real code starts from here*/

#include <gst/gst.h>
#include <glib.h>


static gboolean
bus_call (GstBus     *bus,
          GstMessage *msg,
          gpointer    data)
{
  GMainLoop *loop = (GMainLoop *) data;


  switch (GST_MESSAGE_TYPE (msg)) {

    case GST_MESSAGE_EOS:
      g_print ("End of stream\n");
      g_main_loop_quit (loop);
      break;

    case GST_MESSAGE_ERROR: {
      gchar  *debug;
      GError *error;

      gst_message_parse_error (msg, &error, &debug);
      g_free (debug);

      g_printerr ("Error: %s\n", error->message);
      g_error_free (error);

      g_main_loop_quit (loop);
      break;
    }
    default:
      break;
  }

  return TRUE;
}


static void
on_pad_added (GstElement *element,
              GstPad     *pad,
              gpointer    data)
{
  GstPad *sinkpad;
  GstElement *decoder = (GstElement *) data;

  /* We can now link this pad with the vorbis-decoder sink pad */
  g_print ("Dynamic pad created, linking demuxer/decoder\n");

  sinkpad = gst_element_get_static_pad (decoder, "sink");

  gst_pad_link (pad, sinkpad);

  gst_object_unref (sinkpad);
}


static gboolean
cb_print_position (GstElement *pipeline)
{
  GstFormat fmt = GST_FORMAT_TIME;
  gint64 pos, len;
  if (gst_element_query_position (pipeline, &fmt, &pos)
    && gst_element_query_duration (pipeline, &fmt, &len)) {
    g_print ("Time: %" GST_TIME_FORMAT " / %" GST_TIME_FORMAT "\r",
     GST_TIME_ARGS (pos), GST_TIME_ARGS (len));
  }
  /* call me again */
  return TRUE;
}


int
main (int   argc,
      char *argv[])
{
  GMainLoop *loop;

  GstElement *pipeline, *source, *demuxer, *decoder, *conv, *sink;
  GstBus *bus;

  /* Initialisation */
  gst_init (&argc, &argv);

  loop = g_main_loop_new (NULL, FALSE);


  /* Check input arguments */
  if (argc != 2) {
    g_printerr ("Usage: %s <Ogg/Vorbis filename>\n", argv[0]);
    return -1;
  }


  /* Create gstreamer elements */
  pipeline = gst_pipeline_new ("audio-player");
  source   = gst_element_factory_make ("filesrc",       "file-source");
  demuxer  = gst_element_factory_make ("oggdemux",      "ogg-demuxer");
  decoder  = gst_element_factory_make ("vorbisdec",     "vorbis-decoder");
  conv     = gst_element_factory_make ("audioconvert",  "converter");
  sink     = gst_element_factory_make ("autoaudiosink", "audio-output");

  if (!pipeline || !source || !demuxer || !decoder || !conv || !sink) {
    g_printerr ("One element could not be created. Exiting.\n");
    return -1;
  }

  /* Set up the pipeline */

  /* we set the input filename to the source element */
  g_object_set (G_OBJECT (source), "location", argv[1], NULL);

  /* we add a message handler */
  bus = gst_pipeline_get_bus (GST_PIPELINE (pipeline));
  gst_bus_add_watch (bus, bus_call, loop);
  gst_object_unref (bus);

  /* we add all elements into the pipeline */
  /* file-source | ogg-demuxer | vorbis-decoder | converter | alsa-output */
  gst_bin_add_many (GST_BIN (pipeline),
                    source, demuxer, decoder, conv, sink, NULL);

  /* we link the elements together */
  /* file-source -> ogg-demuxer ~> vorbis-decoder -> converter -> alsa-output */
  gst_element_link (source, demuxer);
  gst_element_link_many (decoder, conv, sink, NULL);
  g_signal_connect (demuxer, "pad-added", G_CALLBACK (on_pad_added), decoder);


  /* Set the pipeline to "playing" state*/
  system("clear");

  g_print ("Welcome to K++ Think Open. Here is the starting point for your application development - a very simple media player to play OGG/Vorbis audio files. Improve it and flaunt it!\nNow playing: %s\n", argv[1]);
  gst_element_set_state (pipeline, GST_STATE_PLAYING);

  /* Iterate */
  g_print ("Running... Wait patiently for the program to terminate after the file is played.\n");
  g_timeout_add (200, (GSourceFunc) cb_print_position, pipeline);
  g_main_loop_run (loop);


  /* Out of the main loop, clean up nicely */
  g_print ("Returned, stopping playback\n");
  gst_element_set_state (pipeline, GST_STATE_NULL);

  g_print ("Deleting pipeline\n");
  gst_object_unref (GST_OBJECT (pipeline));

  g_main_loop_unref(loop);
  return 0;
}



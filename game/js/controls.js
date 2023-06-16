/* ///////////////////////////////////////// INPUTS ///////////////////////////////////////////////////////////////// */
// enable keyboard
me.input.bindKey(me.input.KEY.UP, "up");
me.input.bindKey(me.input.KEY.DOWN, "down");
me.input.bindKey(me.input.KEY.LEFT, "left");
me.input.bindKey(me.input.KEY.RIGHT, "right");
me.input.bindKey(me.input.KEY.A, "left");
me.input.bindKey(me.input.KEY.D, "right");
me.input.bindKey(me.input.KEY.SPACE, "jump", true);
me.input.bindKey(me.input.KEY.Z, "sword", true);
me.input.bindKey(me.input.KEY.X, "slide");
me.input.bindKey(me.input.KEY.Q, "dashleft");
me.input.bindKey(me.input.KEY.E, "dashright");
me.input.bindKey(me.input.KEY.B, "back");

// enable gamepad
me.input.bindGamepad(0, {type: "axes", code: me.input.GAMEPAD.AXES.LX, threshold: -0.5}, me.input.KEY.LEFT); //Left (Axis)
me.input.bindGamepad(0, {type: "axes", code: me.input.GAMEPAD.AXES.LX, threshold: 0.5}, me.input.KEY.RIGHT); //Right (Axis)
me.input.bindGamepad(1, {type: "axes", code: me.input.GAMEPAD.AXES.LY, threshold: 1}, me.input.KEY.DOWN); //Crouch (Axis)

me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.UP, threshold: -0.5}, me.input.KEY.UP); //Up (D-pad)
me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.LEFT, threshold: -0.5}, me.input.KEY.LEFT); //Left (D-pad)
me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.RIGHT, threshold: -0.5}, me.input.KEY.RIGHT); //Right (D-pad)
me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.DOWN, threshold: -0.5}, me.input.KEY.DOWN); //Crouch (D-pad)
me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.FACE_2, threshold: -0.5}, me.input.KEY.X); //Slide (X-button)
me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.FACE_1, threshold: -0.5}, me.input.KEY.SPACE); //Jump (A-button)
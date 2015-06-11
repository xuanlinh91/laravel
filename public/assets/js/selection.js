
/////////////////////////////////////////////////////////
//function GetPreviousSibling get Element's Previous node 
//@param node HTML
//return Element's node Previous || null if this null
/////////////////////////////////////////////////////////
function GetPreviousSibling(node) {
  if (node.previousSibling != null) {
      return node.previousSibling;
  } else {
      return null;
  }
}
/////////////////////////////////////////////////////////
//function GetNextSibling get Element's next node 
//@param node HTML
//return Element's next node || null if this null
/////////////////////////////////////////////////////////
function GetNextSibling(node) {
  if (node.nextSibling != null) {
      return node.nextSibling;
  } else {
      return null;
  }
}
//-----------------------------------------------------------------------------------
//                      SELECTION NODE HTML 
//-----------------------------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////////////
//Function get, check selection in element HTML 
//Function SaveSelection Save cursor's position in Element HTML
//@param Element HTML 
//Function restoreSelection Restore cursor's position when saved
//@param Element HTML 
//@param cursor's postion saved
//Support munti brower
/////////////////////////////////////////////////////////////////////////////////////
if (window.getSelection && document.createRange) {
  saveSelection = function(containerEl) {
    var range = window.getSelection().getRangeAt(0);
    var preSelectionRange = range.cloneRange();
    preSelectionRange.selectNodeContents(containerEl);
    preSelectionRange.setEnd(range.startContainer, range.startOffset);
    var start = preSelectionRange.toString().length;
    return {
        start: start,
        end: start + range.toString().length
    }
  };
  restoreSelection = function(containerEl, savedSel) {
    var charIndex = 0, range = document.createRange();
    range.setStart(containerEl, 0);
    range.collapse(true);
    var nodeStack = [containerEl], node, foundStart = false, stop = false;
    while (!stop && (node = nodeStack.pop())) {
        if (node.nodeType == 3) {
            var nextCharIndex = charIndex + node.length;
            if (!foundStart && savedSel.start >= charIndex && savedSel.start <= nextCharIndex) {
                range.setStart(node, savedSel.start - charIndex);
                foundStart = true;
            }
            if (foundStart && savedSel.end >= charIndex && savedSel.end <= nextCharIndex) {
                range.setEnd(node, savedSel.end - charIndex);
                stop = true;
            }
            charIndex = nextCharIndex;
        } else {
            var i = node.childNodes.length;
            while (i--) {
                nodeStack.push(node.childNodes[i]);
            }
        }
    }
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
  }
} else if (document.selection && document.body.createTextRange) {
  saveSelection = function(containerEl) {
      var selectedTextRange = document.selection.createRange();
      var preSelectionTextRange = document.body.createTextRange();
      preSelectionTextRange.moveToElementText(containerEl);
      preSelectionTextRange.setEndPoint("EndToStart", selectedTextRange);
      var start = preSelectionTextRange.text.length;

      return {
          start: start,
          end: start + selectedTextRange.text.length
      }
  };
  restoreSelection = function(containerEl, savedSel) {
      var textRange = document.body.createTextRange();
      textRange.moveToElementText(containerEl);
      textRange.collapse(true);
      textRange.moveEnd("character", savedSel.end);
      textRange.moveStart("character", savedSel.start);
      textRange.select();
  };
}
/////////////////////////////////////////////////////////////////////////////////
//function doSave Call function savedSelection ( save cursor's position )
//@parram Element HTML
//#return Posirion cursor
//function doRestore call function restoreSelection ( restore cursor's position )
//@parram Elament HTML
//@param position cursor
/////////////////////////////////////////////////////////////////////////////////

function doSave(Elm) {
  return  saveSelection( Elm );
}

function doRestore(Elm, positionCursorSaved) {
    if (positionCursorSaved) {
      restoreSelection(Elm, positionCursorSaved);
    }
}
////////////////////////////////////////////////////////////////////////////////
//function replaceWithOwnChildren replace node HTML
//@param node HTML
///////////////////////////////////////////////////////////////////////////////
function replaceWithOwnChildren(el) {
  var parent = el.parentNode;
  while (el.hasChildNodes()) {
      parent.insertBefore(el.firstChild, el);
  }
  parent.removeChild(el);
}
var getComputedDisplay = (typeof window.getComputedStyle != "undefined") ?
function(el) {
    return window.getComputedStyle(el, null).display;
} :
function(el) {
    return el.currentStyle.display;
};
/////////////////////////////////////////////////////////////////////////////
//fucntion removeSelectionFormatting
// remove selection 
////////////////////////////////////////////////////////////////////////////
function removeSelectionFormatting() {
  var sel = rangy.getSelection();
  if (!sel.isCollapsed) {
      for (var i = 0, range; i < sel.rangeCount; ++i) {
          range = sel.getRangeAt(i);
          range.splitBoundaries();
          var formattingEls = range.getNodes([1], function(el) {
              return el.tagName != "BR" && getComputedDisplay(el) == "inline";
          });
          for (var i = 0, el; el = formattingEls[i++]; ) {
              replaceWithOwnChildren(el);
          }
      }
  }
}
///////////////////////////////////////////////////////////////////////////////
//Function getNodeSelection 
//#return node of cursor focus
//#return node parent of cursor focus
//#return node parent parent of cursor focus
//////////////////////////////////////////////////////////////////////////////
function getNodeSelection () {
if (document.selection) { 
  // for IE
    return {
      node_focus      : document.selection.createRange(), 
      node_parent     : document.selection.createRange().parentElement(),
      node_par_parent : document.selection.createRange().parentElement().parentElement()
    };
  } else {
    // everyone else
    return {
      node_focus      : window.getSelection().anchorNode, 
      node_parent     : window.getSelection().anchorNode.parentNode,
      node_par_parent : window.getSelection().anchorNode.parentNode.parentNode
    };
  }
}

